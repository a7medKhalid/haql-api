<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectAPIController extends Controller
{

    public function getProjects(Request $request){
        $projects_controller = new ProjectController;
        $projects = $projects_controller->getProjects();
        return response()->json($projects);
    }

    public function getTrendingProjects(Request $request){
        $projects_controller = new ProjectController;
        $projects = $projects_controller->getTrendingProjects();
        return response()->json($projects);
    }

    public function getRelatedProjects(Request $request){
        $user = Auth::user();
        $projects_controller = new ProjectController;
        $projects = $projects_controller->getRelatedProjects($user);
        return response()->json($projects);
    }

    public function getPersonalProjects(Request $request){
        $user = Auth::user();
        $projects_controller = new ProjectController;
        $projects = $projects_controller->getPersonalProjects($user);
        return response()->json($projects);
    }

    public function getProject(Request $request, $project_id){
        $projects_controller = new ProjectController;
        $project = $projects_controller->getProject($project_id);
        return response()->json($project);
    }

    public function getProjectGoals(Request $request, $project_id){
        $projects_controller = new ProjectController;
        $goals = $projects_controller->getProjectGoals($project_id);
        return response()->json($goals);
    }

    public function getProjectIssues(Request $request, $project_id){
        $projects_controller = new ProjectController;
        $issues = $projects_controller->getProjectIssues($project_id);
        return response()->json($issues);
    }

    public function getProjectContributions(Request $request,$project_id){
        $request->validate([
            'status' => ['string', 'nullable', 'in:open,accepted,rejected,archived'],
        ]);

        $projects_controller = new ProjectController;
        $contributions = $projects_controller->getProjectContributions($project_id, $request->status);
        return response()->json($contributions);
    }

    public function getProjectComments(Request $request,$project_id){

        $projects_controller = new ProjectController;
        $comments = $projects_controller->getProjectComments($project_id);
        return response()->json($comments);
    }

    public function getProjectContributors(Request $request,$project_id){
        $projects_controller = new ProjectController;
        $contributors = $projects_controller->getProjectContributors($project_id);
        return response()->json($contributors);
    }

    public function createProject(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,owner_id' . $user->id,
            'description' => 'required|string|max:255',
        ]);


        $project_controller = new ProjectController;

        $project = $project_controller->create($user, $request->name, $request->description);



        return response()->json($project);
    }

    public function updateProject(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'string|max:255',
            'project_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $project_controller = new ProjectController;
        $project = $project_controller->update($user, $request->project_id, $request->name, $request->description);

        return response()->json($project);
    }

    public function deleteProject(Request $request)
    {
        $request->validate([
            'project_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $project_controller = new ProjectController;
        $project = $project_controller->delete($user, $request->project_id);

        return response()->json($project);
    }


}
