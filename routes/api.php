<?php

use App\Http\Controllers\API\ContributionsAPIController;
use App\Http\Controllers\API\ProjectAPIController;
use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'projects'], function () {

    Route::get('/{project_id}', [ProjectAPIController::class, 'getProject'] );
    Route::post('/', [ProjectAPIController::class, 'createProject'] )->middleware('auth');
    Route::put('/', [ProjectAPIController::class, 'updateProject'] )->middleware('auth');
    Route::delete('/', [ProjectAPIController::class, 'deleteProject'] )->middleware('auth');

});

Route::group(['prefix' => 'contributions'], function () {
    Route::get('/{contribution_id}', [ContributionsAPIController::class, 'getContribution'] );
    Route::post('/', [ContributionsAPIController::class, 'createContribution'] )->middleware('auth');
    Route::put('/', [ContributionsAPIController::class, 'updateContribution'] )->middleware('auth');
    Route::delete('/', [ContributionsAPIController::class, 'deleteContribution'] )->middleware('auth');
});
