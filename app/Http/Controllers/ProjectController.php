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

    public function getProject($project_id){
        $project = Project::find($project_id);
        return $project;
    }

    public function getProjectGoals($project_id){
        $project = Project::find($project_id);
        $goals = $project->goals()->paginate(10);
        return $goals;
    }

    public function getProjectIssues($project_id){
        $project = Project::find($project_id);
        $issues = $project->issues()->paginate(10);
        return $issues;
    }

    public function getProjectContributions($project_id){
        $project = Project::find($project_id);
        $contributions = $project->contributions()->paginate(10);
        return $contributions;
    }

    public function getProjectComments($project_id){
        $project = Project::find($project_id);
        $comments = $project->comments()->paginate(10);
        return $comments;
    }


    public function create($user, $name, $description, $license){

        $project = Project::create([
            'name' => $name,
            'description' => $description,
            'license' => $license,
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
