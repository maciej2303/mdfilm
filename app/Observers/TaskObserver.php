<?php

namespace App\Observers;

use App\Models\ChangeLog;
use App\Models\ProjectElementComponentVersion;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        ChangeLog::create([
            'authorable_type' => User::class,
            'authorable_id' => $task->user_id,
            'change' => 'dodał pozycję do zrobienia',
            'content' => $task->content,
            'relationable_type' => $task->relationable_type,
            'relationable_id' => $task->relationable_id,
            'model_type' => Task::class,
            'model_id' => $task->id,
        ]);
    }

    /**
     * Handle the Task "updated" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        if ($task->isDirty('checked')) {
            $change = $task->checked ? 'ukończył pozycję do zrobienia' : 'odznaczył pozycję do zrobienia';
            ChangeLog::create([
                'authorable_type' => User::class,
                'authorable_id' => $task->user_id,
                'change' => $change,
                'content' => $task->content,
                'relationable_type' => $task->relationable_type,
                'relationable_id' => $task->relationable_id,
                'model_type' => Task::class,
                'model_id' => $task->id,
            ]);
        }
        if ($task->isDirty('content')) {
            ChangeLog::create([
                'authorable_type' => User::class,
                'authorable_id' => $task->user_id,
                'change' => 'edytował pozycję do zrobienia',
                'content' => $task->content,
                'relationable_type' => $task->relationable_type,
                'relationable_id' => $task->relationable_id,
                'model_type' => Task::class,
                'model_id' => $task->id,
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        ChangeLog::create([
            'authorable_type' => User::class,
            'authorable_id' => $task->user_id,
            'change' => 'usunął pozycję do zrobienia',
            'content' => $task->content,
            'relationable_type' => $task->relationable_type,
            'relationable_id' => $task->relationable_id,
            'model_type' => Task::class,
            'model_id' => $task->id,
        ]);
    }

    /**
     * Handle the Task "restored" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     *
     * @param  \App\Models\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }
}
