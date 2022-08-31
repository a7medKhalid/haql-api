<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{

    public function getProjects(){
        $projects = Project::paginate(10);
        return $projects;
    }

    public function getPersonalProjects($user){
        $projects = $user->projects()->paginate(10);
        return $projects;
    }

    public function getProject($username, $projectName){
        $user = User::where('username', $username)->first();
        $project = $user->projects()->where('name', $projectName)->first();
        return $project;
    }

    public function getProjectGoals($username, $projectName){
        $user = User::where('username', $username)->first();
        $project = $user->projects()->where('name', $projectName)->first();
        $goals = $project->goals()->paginate(10);
        return $goals;
    }

    public function getProjectIssues($username, $projectName){
        $user = User::where('username', $username)->first();
        $project = $user->projects()->where('name', $projectName)->first();
        $issues = $project->issues()->paginate(10);
        return $issues;
    }

    public function getProjectContributions($username, $projectName){
        $user = User::where('username', $username)->first();
        $project = $user->projects()->where('name', $projectName)->first();
        $contributions = $project->contributions()->paginate(10);
        return $contributions;
    }

    public function getProjectComments($username, $projectName){
        $user = User::where('username', $username)->first();
        $project = $user->projects()->where('name', $projectName)->first();
        $comments = $project->comments()->paginate(10);
        return $comments;
    }


    public function create($user, $name, $description){

        $project = Project::create([
            'name' => $name,
            'description' => $description,
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
