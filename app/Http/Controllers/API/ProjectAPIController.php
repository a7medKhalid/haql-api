<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectAPIController extends Controller
{


    public function createProject(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        $user = Auth::user();

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
