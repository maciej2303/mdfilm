<?php

namespace App\Repositories\Comment;

use App\Repositories\Comment\CommentRepositoryInterface;
use Carbon\Carbon;

class CommentRepository implements CommentRepositoryInterface
{
    public function commentsCount($comments, $readComments)
    {
        $commentsCount = 0;
        foreach ($comments as $comment) {
            if (!$readComments->contains($comment)) {
                $commentsCount++;
            }
        }
        return $commentsCount;
    }

    public function checkIfCommentRead($comments, $readComments, $user)
    {
        foreach ($comments as $comment) {
            if ($readComments->contains($comment)) {
                $comment->new = 0;
            } else {
                $comment->new = 1;
                $user->readComments()->attach($comment);
            }
        }
        return $comments;
    }
}
