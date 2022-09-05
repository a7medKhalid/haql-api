<?php

namespace App\Http\Controllers;

use App\Models\Contribution;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    public function create($user, $project_id, $title, $description, $link,)
    {
        $contribution = Contribution::create([
            'title' => $title,
            'description' => $description,
            'link' => $link,

        ]);

        $project = Project::find($project_id);

        $project->contributions()->save($contribution);
        $user->contributions()->save($contribution);

        return $contribution;
    }

    public function update($user, $contribution_id, $status)
    {
        $contribution = Contribution::find($contribution_id);

        if ($user->cannot('update', $contribution)) {
            abort(403, 'You are not authorized to perform this action.');
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

    public function getContribution($contribution_id){
        $contribution = Contribution::find($contribution_id);
        $contribution->contributorName = $contribution->contributor->name;
        return $contribution;
    }

    public function getComments($contribution_id){
        $contribution = Contribution::find($contribution_id);
        $contribution->comments =$contribution->comments()->get();

        return $contribution;
    }

}
