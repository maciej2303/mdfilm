<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskRequest;
use App\Models\ProjectElementComponentVersion;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function store(TaskRequest $request)
    {
        $task = Task::create(array_merge($request->input(), ['user_id' => auth()->id(),]));
        $task->order = $task->relationable->tasks->count();
        $task->save();
        if ($task->relationable_type == ProjectElementComponentVersion::class)
            return redirect()->route('project_element_component_versions.show', $request->relationable_id);
        else
            return redirect()->route('projects.show', $request->relationable_id);
    }

    public function update(Task $task, TaskRequest $request)
    {
        $task->update(array_merge($request->input(), ['user_id' => auth()->id(),]));
        if ($task->relationable_type == ProjectElementComponentVersion::class)
            return redirect()->route('project_element_component_versions.show', $task->relationable_id);
        else
            return redirect()->route('projects.show', $task->relationable_id);
    }

    public function destroy(Request $request)
    {
        try {
            $task = Task::find($request->id);
            $task->delete();
            return response()->json(200);
        } catch (Exception $e) {
        }
    }
}
