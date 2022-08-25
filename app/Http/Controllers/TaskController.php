<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function create(User $user, Goal $goal, $title , $specialitiesIds){

        if ($user->cannot('update', $goal)) {
            abort(403);
        }
        $task = $goal->tasks()->create([
            'title' => $title,
        ]);

        $task->specialities()->attach($specialitiesIds);

        return $task;
    }

    public function update(User $user, Task $task, $title = null , $specialitiesIds = null){

        if ($user->cannot('update', $task)) {
            abort(403);
        }

        $task->update([
            'title' => $title,
        ]);

        $task->specialities()->sync($specialitiesIds);

        return $task;
    }

    public function delete(User $user, Task $task){

        if ($user->cannot('delete', $task)) {
            abort(403);
        }

        $task->delete();

        return $task;
    }
}
