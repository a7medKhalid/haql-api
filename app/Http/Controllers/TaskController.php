<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create($user, $goal_id, $title , $specialtiesIds){

        $goal = Goal::find($goal_id);

        if ($user->cannot('update', $goal)) {
            abort(403);
        }
        $task = $goal->tasks()->create([
            'title' => $title,
        ]);



        $s = $task->specialties()->attach($specialtiesIds);

        $task->save();

        return $task;
    }

    public function update($user, $task_id, $isCompleted){

        $task = Task::find($task_id);

        if ($user->cannot('update', $task)) {
            abort(403);
        }

        $task->update([
            'isCompleted' => $isCompleted,
        ]);

        return $task;
    }

    public function delete($user, $task_id){

        $task = Task::find($task_id);

        if ($user->cannot('delete', $task)) {
            abort(403);
        }

        $task->delete();

        return $task;
    }
}
