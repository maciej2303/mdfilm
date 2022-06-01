<?php

namespace App\Repositories\Comment;

use App\Models\Comment;

interface CommentRepositoryInterface
{
    public function commentsCount($comments, $readComments);
    public function checkIfCommentRead($comments, $readComments, $user);
}
