<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function change(Request $request)
    {
        $task = Task::find($request->id);
        $task->checked = $request->value;
        $task->user_id = auth()->id();
        $task->save();

        $logs = $task->changeLogs()->orderBy('created_at', 'desc')->get();
        $html = view('dashboard.projects.components.tasks.history-box', compact('logs'))->render();
        return response()->json(compact('html'));
    }

    public function tasksById(Request $request)
    {
        $logs = Task::find($request->id)->changeLogs()->orderBy('created_at', 'desc')->get();
        $html = view('dashboard.projects.components.tasks.history-box', compact('logs'))->render();
        return response()->json(compact('html'));
    }

    public function changeOrder(Request $request)
    {
        $orderIt = 0;
        foreach ($request->order as $order) {
            $project = Task::find($order['id']);
            $project->order = $orderIt;
            $project->save();
            $orderIt++;
        }
        return response('success', 200);
    }
}
