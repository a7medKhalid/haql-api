<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    public function create(User $user, Project $project, $title, $description)
    {

        if ($user->cannot('update', $project)) {
           abort(403);
        }

        $project->goals()->create([
            'title' => $title,
            'description' => $description,
        ]);
    }

    public function update(User $user, Goal $goal, $title = null, $description = null, $isCompleted = null)
    {
        if ($user->cannot('update', $goal)) {
            abort(403);
        }

        $goal->update([
            'title' => $title,
            'description' => $description,
            'isCompleted' => $isCompleted,
        ]);
    }

    public function delete(User $user, Goal $goal)
    {
        if ($user->cannot('delete', $goal)) {
            abort(403);
        }

        $goal->delete();
    }



}
