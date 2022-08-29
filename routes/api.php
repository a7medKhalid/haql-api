<?php

use App\Http\Controllers\API\ContributionsAPIController;
use App\Http\Controllers\API\GoalAPIController;
use App\Http\Controllers\API\IssueAPIController;
use App\Http\Controllers\API\ProjectAPIController;

use App\Http\Controllers\API\SpecialtyAPIController;
use App\Http\Controllers\API\TaskAPIController;
use App\Http\Controllers\API\UserAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::group(['prefix' => 'users'], function () {
    Route::get('/user', function (Request $request) {

        return $request->user();

    })->middleware('auth');

    Route::put('', [UserAPIController::class, 'updateUser'])->middleware('auth');
});

Route::group(['prefix' => 'projects'], function () {

    Route::post('/', [ProjectAPIController::class, 'createProject'] )->middleware('auth');
    Route::put('/', [ProjectAPIController::class, 'updateProject'] )->middleware('auth');
    Route::delete('/', [ProjectAPIController::class, 'deleteProject'] )->middleware('auth');

});

Route::group(['prefix' => 'contributions'], function () {
    Route::post('/', [ContributionsAPIController::class, 'createContribution'] )->middleware('auth');
    Route::put('/', [ContributionsAPIController::class, 'updateContribution'] )->middleware('auth');
    Route::delete('/', [ContributionsAPIController::class, 'deleteContribution'] )->middleware('auth');
});


Route::group(['prefix' => 'issues'], function () {
    Route::post('/', [IssueAPIController::class, 'createIssue'] )->middleware('auth');
    Route::put('/', [IssueAPIController::class, 'updateIssue'] )->middleware('auth');
});

Route::group(['prefix' => 'goals'], function () {
    Route::post('/', [GoalAPIController::class, 'createGoal'] )->middleware('auth');
    Route::put('/', [GoalAPIController::class, 'updateGoal'] )->middleware('auth');
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

//Route::group(['prefix' => 'commentes'])
