<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function create(User $user, Project $project, $title, $description)
    {
        $issue = $project->issues()->create([
            'title' => $title,
            'description' => $description,
        ]);

        $issue->user()->associate($user);
        $issue->project()->associate($project);
        $issue->save();

        return $issue;
    }

    public function update(User $user, Issue $issue, $status )
    {
       if ($user->cannot('update', $issue)) {
           abort(403);
       }

       $issue->status = $status;
       $issue->save();

       return $issue;

    }
}
