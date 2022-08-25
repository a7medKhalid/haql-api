<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(User $user, $title, $body ,$commentedType, $commentedId)
    {
        $comment = new Comment();
        $comment->title = $title;
        $comment->body = $body;
        $comment->commentedType = $commentedType;
        $comment->commentedId = $commentedId;
        $comment->user_id = $user->id;
        $comment->save();
    }

    public function update(User $user, Comment $comment, $title, $body)
    {
        if ($user->cannot('update', $comment)) {
            abort(403);
        }

        $comment->title = $title;
        $comment->body = $body;
        $comment->save();
    }

    public function delete(User $user, Comment $comment)
    {
        if ($user->cannot('delete', $comment)) {
            abort(403);
        }

        $comment->delete();
    }

}
