<?php

namespace App\Http\Controllers;

use App\Enums\UserLevel;
use App\Http\Requests\Comments\CommentRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Models\TemporaryUser;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $user = session('temporaryUser') == null ? auth()->user() : session('temporaryUser');
        $label = get_class($user) == TemporaryUser::class ? 'Bez konta' : ($user->level == UserLevel::CLIENT ? 'Klient' : null);
        $comment = Comment::create(array_merge($request->all(), ['authorable_id' => $user->id, 'authorable_type' => get_class($user), 'label' => $label]));
        if (get_class($user) == User::class)
            $user->readComments()->attach($comment);
        if ($request->relationable_type == Project::class)
            return redirect()->route('projects.show', ['project' => $request->relationable_id, 'inner' => $request->inner]);
        else
            return redirect()->route('project_element_component_versions.show', ['projectElementComponentVersion' => $request->relationable_id, 'inner' => $request->inner]);
    }
}
