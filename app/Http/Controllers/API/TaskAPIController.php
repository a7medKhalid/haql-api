<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskAPIController extends Controller
{

    public function createTask(Request $request){

        $request->validate([
            'goal_id' => 'required|integer',
            'title' => 'required|string',
            'specialtiesIds' => 'required|array',
        ]);

        $user = Auth::user();

        $tasks_controller = new TaskController;

        $task = $tasks_controller->create($user, $request->goal_id, $request->title, $request->specialtiesIds);

        return $task;
    }

    public function updateTask(Request $request){

        $request->validate([
            'task_id' => 'required|integer',
            'isCompleted' => 'required|boolean',
        ]);

        $user = Auth::user();

        $tasks_controller = new TaskController;
        $task = $tasks_controller->update($user, $request->task_id, $request->isCompleted);

        return $task;
    }

    public function deleteTask(Request $request){

        $request->validate([
            'task_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $tasks_controller = new TaskController;
        $task = $tasks_controller->delete($user, $request->task_id);

        return $task;
    }

    public function getTask(Request $request){
        $request->validate([
            'task_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $tasks_controller = new TaskController;
        $tasks = $tasks_controller->getTask($request->task_id);

        return $tasks;
    }



}
