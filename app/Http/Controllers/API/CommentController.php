<?php

namespace App\Http\Controllers\API;

use App\Enums\UserLevel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\CommentRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Models\TemporaryUser;
use App\Models\User;
use App\Repositories\Comment\CommentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\EdlExporterService;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $user = session('temporaryUser') == null ? User::find($request->userId) : session('temporaryUser');
        $label = get_class($user) == TemporaryUser::class ? 'Bez konta' : ($user->level == UserLevel::CLIENT ? 'Klient' : null);
        $inner = $request->input('inside_pin') ? 1 : $request->input('inner');
        $comment = Comment::create(array_merge($request->all(), ['inner' => $inner, 'authorable_id' => $user->id, 'authorable_type' => get_class($user), 'label' => $label]));
        if (get_class($user) == User::class)
            $user->readComments()->attach($comment);

        $comment->comment = nl2br($comment->comment);
        $comment->authorable;

        return response()->json([
            'inner' => $request->inner,
            'comment' => $comment,
        ]);
    }

    public function update(CommentRequest $request)
    {
        $comment = Comment::find($request->commentId);
        $comment->comment = $request->comment;
        $comment->start_time = $request->start_time;
        $comment->end_time = $request->end_time;
        $comment->save();
        $comment->authorable;

        return response()->json([
            'inner' => $request->inner,
            'comment' => $comment,
        ]);
    }

    public function comments(Request $request)
    {
        $commentRepository = new CommentRepository();
        $model = $request->modelClass::find($request->modelId);
        $user = $request->userId != null ? User::find($request->userId) : null;
        $commentsCount = 0;
        if(isset($request->forVideoView) && ($user != null && $user->level != UserLevel::CLIENT)) {
            $comments = $model->allComments();
        } else {
            $comments = $request->inner == 0 ? $model->externalComments() : $model->innerComments();
        }
        $innerComments = $request->inner == 1 ? $model->externalComments : $model->innerComments;
        $comments = $comments->orderBy('created_at', 'desc')->get();
        $readComments = $user != null ? $user->readComments : null;
        if ($user != null) {
            $commentsCount = $commentRepository->commentsCount($comments, $readComments);
            $innerCommentsCount = $commentRepository->commentsCount($innerComments, $readComments);
            $comments = $commentRepository->checkIfCommentRead($comments, $readComments, $user);
        }
        foreach ($comments as $comment) {
            $comment->comment = nl2br($comment->comment);
            if ($comment->label == "Klient" && ($user != null && $user->level != UserLevel::CLIENT))
                $comment->client = true;
            else
                $comment->client = false;
        }
        return response()->json([
            'comments' => $comments,
            'commentsCount' => $commentsCount,
            'innerCommentsCount' => $innerCommentsCount ?? 0,
        ]);
    }

    public function destroy(Request $request)
    {
        try {
            $comment = Comment::find($request->commentId);
            $comment->delete();
            return response()->json(200);
        } catch (Exception $e) {
        }
    }
    public function edlcomments(Request $request)
    {
        $export_servce = new EdlExporterService();
        $text = $export_servce->getComments($request);

        return response()->json([
            'text' => $text]);
            
    }
}
