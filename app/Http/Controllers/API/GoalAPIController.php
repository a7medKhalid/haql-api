<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GoalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalAPIController extends Controller
{
    public function createGoal(Request $request){

        $request->validate([
            'project_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $user = Auth::user();

        $goals_controller = new GoalController;

        $goal = $goals_controller->create($user, $request->project_id, $request->title, $request->description);

        return $goal;
    }

    public function updateGoalStatus(Request $request){

        $request->validate([
            'goal_id' => 'required|integer',
            'isCompleted' => 'required|boolean',
        ]);

        $user = Auth::user();

        $goals_controller = new GoalController;
        $goal = $goals_controller->update($user, $request->goal_id, $request->isCompleted);

        return $goal;
    }


    public function deleteGoal(Request $request){

        $request->validate([
            'goal_id' => 'required|integer',
        ]);

        $user = Auth::user();

        $goals_controller = new GoalController;

        $goal = $goals_controller->delete($user, $request->goal_id);

        return $goal;
    }


    public function getGoal(Request $request, $goal_id){

            $user = Auth::user();

            $goals_controller = new GoalController;

            $goal = $goals_controller->getGoal($goal_id);

            return $goal;
    }


}
