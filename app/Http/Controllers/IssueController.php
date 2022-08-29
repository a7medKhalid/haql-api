<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function create($user, $title, $description, $project_id)
    {

        $project = Project::find($project_id);


        $issue = $project->issues()->create([
            'title' => $title,
            'description' => $description,
        ]);

        $issue->user()->associate($user);
        $issue->project()->associate($project);
        $issue->save();

        return $issue;
    }

    public function update($user, $status, $issue_id )
    {

        $issue = Issue::find($issue_id);

        if ($user->cannot('update', $issue)) {
           abort(403);
       }

       $issue->status = $status;
       $issue->save();

       return $issue;

    }
}
