<?php

use App\Http\Controllers\API\CommentsAPIController;
use App\Http\Controllers\API\ContributionsAPIController;
use App\Http\Controllers\API\GoalAPIController;
use App\Http\Controllers\API\IssueAPIController;
use App\Http\Controllers\API\ProjectAPIController;

use App\Http\Controllers\API\SpecialtyAPIController;
use App\Http\Controllers\API\TaskAPIController;
use App\Http\Controllers\API\UserAPIController;
use App\Models\Goal;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {

    return $request->user();

})->middleware('auth');


Route::group(['prefix' => 'users'], function () {

    Route::put('', [UserAPIController::class, 'updateUser'])->middleware('auth');
});

Route::group(['prefix' => 'projects'], function () {
Route::get('', [ProjectAPIController::class, 'getProjects']);
    Route::get('/personal', [ProjectAPIController::class, 'getPersonalProjects'])->middleware('auth');

    Route::post('/', [ProjectAPIController::class, 'createProject'] )->middleware('auth');
    Route::put('/', [ProjectAPIController::class, 'updateProject'] )->middleware('auth');
    Route::delete('/', [ProjectAPIController::class, 'deleteProject'] )->middleware('auth');

});

Route::group(['prefix' => 'contributions'], function () {
    Route::post('/', [ContributionsAPIController::class, 'createContribution'] )->middleware('auth');
    Route::put('/', [ContributionsAPIController::class, 'updateContributionStatus'] )->middleware('auth');
    Route::delete('/', [ContributionsAPIController::class, 'deleteContribution'] )->middleware('auth');
});


Route::group(['prefix' => 'issues'], function () {
    Route::post('/', [IssueAPIController::class, 'createIssue'] )->middleware('auth');
    Route::put('/', [IssueAPIController::class, 'updateIssueStatus'] )->middleware('auth');
});

Route::group(['prefix' => 'goals'], function () {
    Route::post('/', [GoalAPIController::class, 'createGoal'] )->middleware('auth');
    Route::put('/', [GoalAPIController::class, 'updateGoalStatus'] )->middleware('auth');
    Route::delete('/', [GoalAPIController::class, 'deleteGoal'] )->middleware('auth');
});

Route::group(['prefix' => 'tasks'], function () {
    Route::post('/', [TaskAPIController::class, 'createTask'] )->middleware('auth');
    Route::put('/', [TaskAPIController::class, 'updateTask'] )->middleware('auth');
    Route::delete('/', [TaskAPIController::class, 'deleteTask'] )->middleware('auth');
});

Route::group(['prefix' => 'specialties'], function () {
    Route::post('/', [SpecialtyAPIController::class, 'createSpecialty'] )->middleware('auth');
});

Route::group(['prefix' => 'comments'], function (){
    Route::post('/', [CommentsAPIController::class, 'createComment'] )->middleware('auth');
    Route::put('/', [CommentsAPIController::class, 'updateComment'] )->middleware('auth');
    Route::delete('/', [CommentsAPIController::class, 'deleteComment'] )->middleware('auth');
});

//web pages routes

//permissions route
Route::get('/permissions', function (Request $request) {
    $request->validate([
        'model' => ['required', Rule::in(['project', 'goal', 'task', 'issue'])],
        'model_id' => ['required', 'integer'],

        'permission' => ['required', Rule::In(['create', 'update', 'delete'])],
    ]);

    $model = $request->model;
    $model_id = $request->model_id;

    if($model === 'project') {
        $model = Project::find($model_id);
    } else if($model === 'goal') {
        $model = Goal::find($model_id);
    } else if($model === 'task') {
        $model = Task::find($model_id);
    } else if($model === 'issue') {
        $model = Issue::find($model_id);
    }

    $user = $request->user();
    $permission = $request->permission;

    if ($permission === 'create') {
        if ($user->can('create', $model)) {
            return response()->json(['message' => true]);
        } else {
            return response()->json(['message' => false]);
        }
    } else if ($permission === 'update') {
        if ($user->can('update', $model)) {
            return response()->json(['message' => true]);
        } else {
            return response()->json(['message' => false]);
        }
    } else if ($permission === 'delete') {
        if ($user->can('delete', $model)) {
            return response()->json(['message' => true]);
        } else {
            return response()->json(['message' => false]);
        }
    }

})->middleware('auth');

Route::group(['prefix' => 'users'], function (){
    Route::get('/{username}', [UserAPIController::class, 'getUser'] );
    Route::get('/{username}/projects', [UserAPIController::class, 'getUserProjects'] );
    Route::get('/{username}/contributions', [UserAPIController::class, 'getUserContributions'] );
    Route::get('/{username}/specialties', [UserAPIController::class, 'getUserSpecialties'] );
});

Route::group(['prefix' => 'projects'], function () {
    Route::get('/', [ProjectAPIController::class, 'getProjects']);
    Route::get('/personal', [ProjectAPIController::class, 'getPersonalProjects'])->middleware('auth');
    Route::get('/{username}/{projectName}', [ProjectAPIController::class, 'getProject']);
    Route::get('/{username}/{projectName}/goals', [ProjectAPIController::class, 'getProjectGoals']);
    Route::get('/{username}/{projectName}/issues', [ProjectAPIController::class, 'getProjectIssues']);
    Route::get('/{username}/{projectName}/contributions', [ProjectAPIController::class, 'getProjectContributions']);
    Route::get('{username}/{projectName}/comments', [ProjectAPIController::class, 'getProjectComments']);
});

Route::group(['prefix' => 'goals'], function () {
    Route::get('/{goal_id}', [GoalAPIController::class, 'getGoal']);
});

Route::group(['prefix' => 'tasks'], function () {
    Route::get('/{task_id}', [TaskAPIController::class, 'getTask']);
});

Route::group(['prefix' => 'issues'], function () {
    Route::get('/{issue_id}', [IssueAPIController::class, 'getIssue']);
    Route::get('/{issue_id}/comments', [IssueAPIController::class, 'getIssueComments']);
});

Route::group(['prefix' => 'contributions'], function () {
    Route::get('/{contribution_id}', [ContributionsAPIController::class, 'getContribution']);
    Route::get('/{contribution_id}/comments', [ContributionsAPIController::class, 'getContributionComments']);
});

Route::group(['prefix' => 'comments'], function () {
    Route::get('/{comment_id}', [CommentsAPIController::class, 'getCommentComments']);
});






