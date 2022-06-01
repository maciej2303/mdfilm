<?php

namespace App\Http\Controllers\API;

use App\Enums\UserLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\AttachmentRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Models\ProjectElementComponentVersion;
use App\Models\TemporaryUser;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function getAttachments(Request $request)
    {
        $model = $request->modelClass::find($request->modelId);
        $user = $request->userId != null ? User::find($request->userId) : null;
        $inner = 0;
        if ($user != null) {
            $inner = $user->level == UserLevel::ADMIN || $user->level == UserLevel::WORKER ? 1 : 0;
        }
        $attachments = $request->modelClass == Project::class ? Attachment::find($model->allAttachments($inner)) : $model->attachments;
        $attachments = $attachments->sortByDesc('created_at')->values();
        foreach ($attachments as $attachment) {
            $attachment->attachment = nl2br($attachment->attachment);
            $attachment->url = Storage::disk('s3')->temporaryUrl(
                $attachment->url,
                Carbon::now()->addMinutes(20)
            );
            if ($attachment->relationable_type == Project::class) {
                $attachment->addedIn = '';
                $attachment->href = 'Karta projektu';
                $attachment->route = route('projects.show', $attachment->relationable_id);
            } else {
                $projectElementComponentVersion = ProjectElementComponentVersion::find($attachment->relationable_id);
                $attachment->addedIn =
                    $projectElementComponentVersion->projectElementComponent->projectElement->name . ' > ' . $projectElementComponentVersion->projectElementComponent->name . ' > ';
                $attachment->href = $projectElementComponentVersion->version;
                $attachment->route = route('project_element_component_versions.show', $attachment->relationable_id);
            }

            if ($attachment->label == "Klient" && ($user != null && $user->level != UserLevel::CLIENT))
                $attachment->client = true;
            else
                $attachment->client = false;
        }
        return response()->json([
            'attachments' => $attachments,
        ]);
    }
    public function store(AttachmentRequest $request)
    {
        $user = session('temporaryUser') == null ? User::find($request->userId) : session('temporaryUser');
        $label = get_class($user) == TemporaryUser::class ? 'Bez konta' : ($user->level == UserLevel::CLIENT ? 'Klient' : null);
        $disk = Storage::disk('s3');
        $class = app($request->relationable_type);
        $model = $class::find($request->relationable_id);
        $hash = '1';
        $path = '';
        $route = '/';
        $addedIn = '';
        if (get_class($model) == Project::class) {
            $hash = $model->hashed_url;
            $href = 'Karta projektu';
            $route = route('projects.show', $model->id);
        }
        if (get_class($model) == ProjectElementComponentVersion::class) {
            $hash = $model->project()->hashed_url;
            $path = '/' . $model->projectElementComponent->projectElement->name . '/' . $model->projectElementComponent->name . '/' . $model->version;
            $addedIn = $model->projectElementComponent->projectElement->name . ' > ' . $model->projectElementComponent->name . ' > ';
            $href = $model->version;
            $route = route('project_element_component_versions.show', $model->id);
        }
        $folder = 'project-' . $hash . $path;
        $file = $request->file;
        $extension = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_name = md5($file->getClientOriginalName());
        $url = $disk->put($folder, $file, $file_name, 'public');

        $attachment = Attachment::create(array_merge($request->all(), [
            'extension' => $extension,
            'url' => $url,
            'authorable_id' => $user->id,
            'authorable_type' => get_class($user),
            'label' => $label
        ]));

        $attachment->authorable;
        $attachment->addedIn = $addedIn;
        $attachment->href = $href;
        $attachment->route = $route;
        $attachment->pinned = false;
        $attachment->url = Storage::disk('s3')->temporaryUrl(
            $attachment->url,
            Carbon::now()->addMinutes(20)
        );


        return response()->json([
            'attachment' => $attachment,
        ]);
    }
    public function pin(Request $request)
    {
        $attachment = Attachment::find($request->input('id'));
        $attachment->pinned = !$attachment->pinned;
        $attachment->save();

        return response()->json([
            'pinned' => $attachment->pinned,
        ]);
    }
}
