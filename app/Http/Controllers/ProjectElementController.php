<?php

namespace App\Http\Controllers;

use App\Enums\ProjectComponentType;
use App\Http\Requests\ProjectElement\ProjectElementRequest as ProjectElementProjectElementRequest;
use App\Http\Requests\ProjectElements\ProjectElementRequest;
use App\Models\Component;
use App\Models\Project;
use App\Models\ProjectElement;
use App\Models\ProjectElementComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectElementController extends Controller
{
    public function create(Project $project)
    {
        $components = [];
        foreach (ProjectComponentType::getValues() as $component) {
            $components[] = ProjectComponentType::getDescription($component);
        }
        return view('dashboard.projects.elements.form')
            ->with('components', $components)
            ->with('project', $project);
    }

    public function store(ProjectElementRequest $request)
    {
        $project_element = ProjectElement::create($request->all());
        foreach ($request->components as $component) {
            ProjectElementComponent::create(['name' => $component, 'project_element_id' => $project_element->id]);
        }
        return redirect()->route('projects.show', $request->project_id);
    }

    public function edit(ProjectElement $projectElement)
    {
        $components = [];
        foreach (ProjectComponentType::getValues() as $component) {
            $components[] = ProjectComponentType::getDescription($component);
        }
        return view('dashboard.projects.elements.form')
            ->with('components', $components)
            ->with('projectElement', $projectElement)
            ->with('project', $projectElement->project);
    }

    public function update(ProjectElement $projectElement, ProjectElementRequest $request)
    {
        $projectElement->update($request->all());
        foreach ($request->components as $component) {
            if (!$projectElement->components->contains('name', $component)) {
                ProjectElementComponent::create(['name' => $component, 'project_element_id' => $projectElement->id]);
            }
        }
        foreach ($projectElement->components as $component) {
            if (!in_array($component->name, $request->components) && $component->versions->isEmpty()) {
                $component->delete();
            }
        }
        return redirect()->route('projects.show', $request->project_id);
    }

    public function destroy(Request $request)
    {
        try {
            $projectElement = ProjectElement::find($request->id);
            $project = $projectElement->project;
            $folder = 'project-' . $project->hashed_url . '/' . $projectElement->name;
            Storage::disk('s3')->deleteDirectory($folder);
            $projectElement->delete();
            return response()->json([
                'route' => route('projects.show', $project->id),
            ]);
        } catch (Exception $e) {
        }
    }

    public function changeOrder(Request $request)
    {
        try {
            foreach ($request->order as $key => $order) {
                $projectElement = ProjectElement::find($order['id']);
                $projectElement->order = $key;
                $projectElement->save();
            }
            return response()->json(200);
        } catch (Exception $e) {
        }
    }
}
