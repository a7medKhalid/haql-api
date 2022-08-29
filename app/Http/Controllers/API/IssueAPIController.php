<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IssueController;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IssueAPIController extends Controller
{

    public function createIssue(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'project_id' => ['required', 'integer'],
        ]);

        $issueController = new IssueController();
        $issue = $issueController->create($request->user, $request->title, $request->body, $request->project_id);

        return $issue;
    }

    public function updateIssue(Request $request)
    {
        $request->validate([
            'status' => ['required', Rule::in(['open', 'closed'])],
            'issue_id' => ['required', 'integer'],
        ]);

        $user = Auth::user();

        $issueController = new IssueController();
        $issue = $issueController->update($user,  $request->status, $request->issue_id);

        return $issue;
    }


}
