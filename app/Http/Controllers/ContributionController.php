<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Project;
use App\Services\ReposService;
use Illuminate\Support\Facades\Log;

class ContributionController extends Controller
{
    public function create($user, $project_id, $title, $description, $files)
    {
        $contribution = Contribution::create([
            'title' => $title,
            'description' => $description,
        ]);

        $project = Project::find($project_id);

        $project->contributions()->save($contribution);
        $user->contributions()->save($contribution);

        $repos_service = new ReposService($project->directory);
        $repos_service->uploadFiles($contribution->id, $files);

        return $contribution;
    }

    public function uploadFiles($contribution_id, $files)
    {
        $contribution = Contribution::find($contribution_id);

        $project = $contribution->project;


        $repos_service = new ReposService($project->directory);

        $repos_service->uploadFiles($contribution, $files);


        return $contribution;
    }

    public function update($user, $contribution_id, $status)
    {
        $contribution = Contribution::find($contribution_id);

        if ($user->cannot('update', $contribution)) {
            abort(403, 'You are not authorized to perform this action.');
        }

        if ($status == 'accepted' ) {
            $project = $contribution->project;

            $repos_service = new ReposService($project->directory);

            $repos_service->merge($contribution->id);

            Log::write("debug", "merged");
        }

        $contribution->update([
            'status' => $status,
        ]);

        return $contribution;
    }

    public function delete($user, $contribution_id)
    {
        $contribution = Contribution::find($contribution_id);

        if ($user->cannot('delete', $contribution)) {
            abort(403, 'You are not authorized to perform this action.');
        }

        $contribution->delete();

        return $contribution;
    }

    public function getContribution($contribution_id)
    {
        $contribution = Contribution::find($contribution_id);
        $contribution->contributorName = $contribution->contributor->name;

        return $contribution;
    }

    public function getComments($contribution_id)
    {
        $contribution = Contribution::find($contribution_id);

        $contribution->comments = $contribution->comments()->paginate(10)->through(function ($comment) {
            $comment->commenterName = $comment->user->name;
            $comment->commenterUsername = $comment->user->username;

            $comment->replysCount = $comment->comments()->count();

            return $comment;
        });

        return $contribution;
    }
}
