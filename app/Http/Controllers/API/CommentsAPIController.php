<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\CommentController;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Contribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CommentsAPIController extends Controller
{
    public function createComment(Request $request){
       $request->validate([
           'title' => 'required|string',
           'body' => 'required|string',
           'commentedType' => ['required', 'string', Rule::in(['comment', 'project', 'issue', 'contribution'])],
           'commented_id' => 'required|integer',
       ]);
        $user = Auth::user();
        $comments_controller = new CommentController;
        $comment = $comments_controller->create($user, $request->title, $request->body, $request->commentedType, $request->commented_id);

        return response()->json($comment);

    }

    public function updateComment(Request $request){
        $request->validate([
            'comment_id' => 'required|integer',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);
        $user = Auth::user();
        $comments_controller = new CommentController;
        $comment = $comments_controller->update($user, $request->comment_id, $request->title, $request->body);

        return response()->json($comment);
    }

    public function deleteComment(Request $request){
        $request->validate([
            'comment_id' => 'required|integer',
        ]);
        $user = Auth::user();
        $comments_controller = new CommentController;
        $comment = $comments_controller->delete($user, $request->comment_id);

        return response()->json($comment);
    }

    public function getCommentComments(Request $request){
        $request->validate([
            'comment_id' => 'required|integer',
        ]);
        $comments_controller = new CommentController;
        $comments = $comments_controller->getComment($request->comment_id);

        return response()->json($comments);
    }


}
