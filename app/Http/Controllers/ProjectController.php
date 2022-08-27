<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{

    public function create($user, $name, $description,$licnse,$licnse_url){

        $project = Project::create([
            'name' => $name,
            'description' => $description,
            'license' => $licnse,
            'license_url' => $licnse_url,
        ]);

        $user->projects()->save($project);

        return $project;

    }

    public function update($user, $project_id, $name = null, $description = null){

        $project = Project::find($project_id);

        if ($user->cannot('update', $project)) {
            abort(403, 'You are not authorized to perform this action.');
        }

        $project->update([
            'name' => $name,
            'description' => $description,
        ]);


        return $project;

    }

    public function delete($user, $project_id){

        $project = Project::find($project_id);

        if ($user->cannot('delete', $project)) {
            abort(403, 'You are not authorized to perform this action.');
        }

        //if project has contributions, abort

        if ($project->contributions()->count() > 0){
            abort(403, 'You cannot delete a project that has contributions.');
        }

        $project->delete();

        return $project;

    }


}
