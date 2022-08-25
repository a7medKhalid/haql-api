<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function create(User $user, $name, $description){

        $project = Project::create([
            'name' => $name,
            'description' => $description,
        ]);

        $user->projects()->save($project);

        return $project;

    }

    public function update(User $user, Project $project, $name = null, $description = null){

        if ($user->cannot('update', $project)) {
            abort(403);
        }

        $project->update([
            'name' => $name,
            'description' => $description,
        ]);


        return $project;

    }

    public function delete(User $user, Project $project){

        if ($user->cannot('delete', $project)) {
            abort(403);
        }

        $project->delete();

        return $project;

    }


}
