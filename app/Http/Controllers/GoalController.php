<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Project;

class GoalController extends Controller
{
    public function create($user, $project_id, $title, $description)
    {
        $project = Project::find($project_id);

        if ($user->cannot('update', $project)) {
            abort(403);
        }

        $goal = $project->goals()->create([
            'title' => $title,
            'description' => $description,
        ]);

        return $goal;
    }

    public function update($user, $goal_id, $isCompleted = null)
    {
        $goal = Goal::find($goal_id);

        if ($user->cannot('update', $goal)) {
            abort(403);
        }

        $goal->update([
            'isCompleted' => $isCompleted,
        ]);

       if ($isCompleted) {
           //update all tasks of this goal
           $goal->tasks()->update([
               'isCompleted' => true,
           ]);
        }

        return $goal;
    }

    public function delete($user, $goal_id)
    {
        $goal = Goal::find($goal_id);

        if ($user->cannot('delete', $goal)) {
            abort(403);
        }

        $goal->delete();

        return $goal;
    }

    public function getGoal($goal_id)
    {
        $goal = Goal::where('id', $goal_id)->first();
        $tasks = $goal->tasks()->get();

        $goal->tasks = $tasks;

        return $goal;
    }
}
