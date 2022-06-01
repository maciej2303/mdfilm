<?php

namespace App\Http\Controllers;

use App\Http\Requests\Projects\YoutubeVideoRequest;
use App\Http\Requests\Projects\ProjectElementComponentVersionRequest;
use App\Http\Requests\Projects\ProjectVersionEditRequest;
use App\Http\Requests\Projects\ProjectVersionPDFRequest;
use App\Models\ProjectElement;
use App\Models\ProjectElementComponent;
use App\Models\ProjectElementComponentVersion;
use App\Models\ProjectElementComponentVersionAcceptance;
use App\Repositories\Comment\CommentRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage as FacadesStorage;
use Youtube;
use Storage;

class ProjectElementComponentVersionController extends Controller
{
    public function create(ProjectElementComponent $projectElementComponent)
    {
        $versionNumber = 'v.' . ($projectElementComponent->externalVersions->count() + 1);
        $innerVersionNumber = 'v0.' . ($projectElementComponent->innerVersions->count() + 1);
        return view('dashboard.projects.versions.form')
            ->with('versionNumber', $versionNumber)
            ->with('innerVersionNumber', $innerVersionNumber)
            ->with('projectElementComponent', $projectElementComponent)
            ->with('project', $projectElementComponent->project());
    }

    public function store(ProjectElementComponentVersionRequest $request)
    {
        $projectElementComponent = ProjectElementComponent::find($request->project_element_component_id);
        $projectElementComponent->setVersionsActiveToFalse();
        $projectElementComponentVersion = ProjectElementComponentVersion::create($request->all());
        $decisivePersons = $projectElementComponentVersion->inner == 0 ? $projectElementComponent->projectElement->project->contactPersons : $projectElementComponent->projectElement->project->managers;
        foreach ($decisivePersons as $decistionMaker) {
            ProjectElementComponentVersionAcceptance::create(['project_element_component_version_id' => $projectElementComponentVersion->id, 'user_id' => $decistionMaker->id]);
        }
        return redirect()->route('project_element_component_versions.show', $projectElementComponentVersion);
    }

    public function edit(ProjectElementComponentVersion $projectElementComponentVersion)
    {
        return view('dashboard.projects.versions.edit')
            ->with('projectElementComponentVersion', $projectElementComponentVersion);
    }

    public function update(ProjectElementComponentVersion $projectElementComponentVersion, ProjectVersionEditRequest $request)
    {
        $projectElementComponentVersion->version = $request->version;
        if ($request->link) {
            $youtube_id = $this->prepareYoutubeLink($request->link);
            $projectElementComponentVersion->youtube_url = $youtube_id;
        }
        if ($request->pdf) {
            Storage::disk('s3')->delete($projectElementComponentVersion->pdf);
            $pdf = $this->savePDF($projectElementComponentVersion, $request->pdf);
            $projectElementComponentVersion->pdf = $pdf;
        }
        $projectElementComponentVersion->save();

        return redirect()->route('project_element_component_versions.show', $projectElementComponentVersion->id);
    }

    public function destroy(Request $request)
    {
        try {
            $projectElementComponentVersion = ProjectElementComponentVersion::find($request->id);
            $project = $projectElementComponentVersion->project();
            $projectElementComponent = $projectElementComponentVersion->projectElementComponent;
            $projectElement = $projectElementComponent->projectElement;
            $folder = 'project-' . $project->hashed_url . '/' . $projectElement->name . '/' . $projectElementComponent->name . '/' . $projectElementComponentVersion->version;
            Storage::disk('s3')->deleteDirectory($folder);
            $projectElementComponentVersion->delete();
            return response()->json([
                'route' => route('projects.show', $project->id),
            ]);
        } catch (Exception $e) {
        }
    }

    public function show(ProjectElementComponentVersion $projectElementComponentVersion)
    {
        $disabled = $projectElementComponentVersion->projectElementComponent->versions()->latest()->first()->id == $projectElementComponentVersion->id ? 'false' : 'true';
        $projectElementComponentVersion->youtube_url = str_replace('https://www.youtube.com/embed/', '', $projectElementComponentVersion->youtube_url);
        $avaliableAcceptance = false;
        $user = auth()->check() ? auth()->user() : 'null';

        foreach ($projectElementComponentVersion->acceptances as $acceptance) {
            $acceptance->user;
            if ($user != 'null' && $acceptance->user->id == $user->id && $acceptance->acceptance == false)
                $avaliableAcceptance = true;
        }
        $versions = $projectElementComponentVersion->projectElementComponent->versions()->orderBy('created_at', 'desc')->get();

        if ($projectElementComponentVersion->pdf != null) {
            $projectElementComponentVersion->pdf = FacadesStorage::disk('s3')->temporaryUrl(
                $projectElementComponentVersion->pdf,
                Carbon::now()->addMinutes(60)
            );
        }

        $project = $projectElementComponentVersion->projectElementComponent->projectElement->project;
        $view = view('dashboard.projects.versions.show');
        if ($project->simple) {
            $projectElementComponentVersion->simple = 1;
            $view = view('dashboard.projects.simple.show');
        }

        return $view
            ->with('projectElementComponentVersion', $projectElementComponentVersion)
            ->with('projectElementComponentVersionClass', get_class($projectElementComponentVersion))
            ->with('project', $project)
            ->with('versions', $versions)
            ->with('user', $user)
            ->with('avaliableAcceptance', $avaliableAcceptance)
            ->with('disabled', $disabled)
            ->with('users', $project->allMembers());
    }

    public function changeStatus(ProjectElementComponentVersion $projectElementComponentVersion, $status)
    {
        $projectElementComponentVersion->status = $status;
        $projectElementComponentVersion->save();
        return redirect()->route('project_element_component_versions.show', $projectElementComponentVersion);
    }

    public function storeVideo(YoutubeVideoRequest $request)
    {
        $projectElementComponentVersion = ProjectElementComponentVersion::find($request->project_element_component_version_id);
        $video = Youtube::upload(Storage::path(str_replace('storage/', 'public/', $request->storage_path)), [
            'title'       => $request->input('title'),
            'description' => $request->input('description')
        ], 'unlisted', 'unlisted');

        $projectElementComponentVersion->youtube_url = $video->getVideoId();
        $projectElementComponentVersion->save();

        Storage::deleteDirectory('/public/upload/video');
        Storage::deleteDirectory('/chunks');
        return redirect()->route('project_element_component_versions.show', $projectElementComponentVersion);
    }

    public function storeVideoByLink(Request $request)
    {
        $projectElementComponentVersion = ProjectElementComponentVersion::find($request->project_element_component_version_id);
        $validated = $request->validate([
            'link' => 'required|url',
        ]);
        $youtube_id = $this->prepareYoutubeLink($request->link);
        $projectElementComponentVersion->youtube_url = $youtube_id;
        $projectElementComponentVersion->save();
        return redirect()->route('project_element_component_versions.show', $projectElementComponentVersion);
    }

    private function prepareYoutubeLink($link)
    {
        $youtube_id = null;
        try {
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $link, $match);
            $youtube_id = $match[1];
        } catch (Exception $e) {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                'link' => ['Błędny link'],
            ]);
            throw $error;
        }
        return $youtube_id;
    }

    public function storePDF(ProjectVersionPDFRequest $request)
    {
        $projectElementComponentVersion = ProjectElementComponentVersion::find($request->project_element_component_version_id);
        $url = $this->savePDF($projectElementComponentVersion, $request->pdf);

        $projectElementComponentVersion->pdf = $url;
        $projectElementComponentVersion->save();

        return redirect()->route('project_element_component_versions.show', $projectElementComponentVersion);
    }

    public function savePDF($projectElementComponentVersion, $pdf)
    {
        $disk = Storage::disk('s3');
        $pdf = $pdf;
        $file_name = md5($pdf->getClientOriginalName());
        $folder = 'project-' . $projectElementComponentVersion->project()->hashed_url . '/' . $projectElementComponentVersion->projectElementComponent->projectElement->name . '/' . $projectElementComponentVersion->projectElementComponent->name . '/' . $projectElementComponentVersion->version;
        $url = $disk->put($folder, $pdf, $file_name);

        return $url;
    }
}
