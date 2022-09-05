<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update($user, $bio , $specialtiesIds , $name){
        if ($user->cannot('update', $user)) {
            abort(403);
        }

        $user->update([
            'name' => $name,
            'bio' => $bio,
        ]);

        $user->specialties()->sync($specialtiesIds);

        $user->save();

        return $user;
    }

    public function getUser($username){
        $user = User::where('username', $username)->first();
        $specialties = $user->specialties()->paginate(10);
        $user->specialties = $specialties;

        return $user;
    }

    public function getUserSpecialties($username){
        $user = User::where('username', $username)->first();
        $specialties = $user->specialties()->paginate(10);

        return $specialties;
    }

    public function getUserProjects($username){
        $user = User::where('username', $username)->first();
        $projects = $user->projects()->paginate(10)->through(function ($project) {
           $project->contributionsCount = $project->contributions()->count();
              $project->issuesCount = $project->issues()->count();
            return $project;
        });
        return $projects;
    }

    public function getUserContributions($username){
        $user = User::where('username', $username)->first();

        $contributions = $user->contributions()->paginate(10)->through(function ($contribution) {
            $project = $contribution->project;
            $contribution->projectName = $project->name;
            return $contribution;
        });
        return $contributions;
    }

    public function getLatestUsers(){
        $users = User::latest()->paginate(10)->through(function ($user) {
           $user->projectsCount = $user->projects()->count();
           $user->contributionsCount = $user->contributions()->count();
           $user->issuesCount = $user->issues()->count();
            return $user;
        });
        return $users;
    }

    public function getMostContributors(){
        $users = User::withCount('contributions')->orderBy('contributions_count', 'desc')->paginate(10)->through(function ($user) {
            $user->projectsCount = $user->projects()->count();
            $user->contributionsCount = $user->contributions()->count();
            $user->issuesCount = $user->issues()->count();
            return $user;
        });;
        return $users;
    }

    public function getMostProjects(){
        $users = User::withCount('projects')->orderBy('projects_count', 'desc')->paginate(10)->through(function ($user) {
            $user->projectsCount = $user->projects()->count();
            $user->contributionsCount = $user->contributions()->count();
            $user->issuesCount = $user->issues()->count();
            return $user;
        });;
        return $users;
    }
}
