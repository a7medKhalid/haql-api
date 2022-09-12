<?php

namespace App\Http\Controllers;

use App\Models\Comment;

class CommentController extends Controller
{
    public function create($user, $title, $body, $commentedType, $commented_id)
    {
        $comment = new Comment();
        $comment->title = $title;
        $comment->body = $body;
        $comment->commentedType = $commentedType;
        $comment->commented_id = $commented_id;
        $comment->user_id = $user->id;
        $comment->save();

        return $comment;
    }

    public function update($user, $comment_id, $title, $body)
    {
        $comment = Comment::find($comment_id);

        if ($user->cannot('update', $comment)) {
            abort(403);
        }

        $comment->title = $title;
        $comment->body = $body;
        $comment->save();

        return $comment;
    }

    public function delete($user, $comment_id)
    {
        $comment = Comment::find($comment_id);

        if ($user->cannot('delete', $comment)) {
            abort(403);
        }

        $comment->delete();

        return $comment;
    }

    public function getComment($comment_id)
    {
        $comment = Comment::find($comment_id);

        $comment->commenterName = $comment->user->name;
        $comment->commenterUsername = $comment->user->username;
        $comment->replysCount = $comment->comments()->count();

        $comment->comments = $comment->comments()->paginate(10)->through(function ($comment) {
            $comment->commenterName = $comment->user->name;
            $comment->commenterUsername = $comment->user->username;

            $comment->replysCount = $comment->comments()->count();

            return $comment;
        });

        return $comment;
    }
}
