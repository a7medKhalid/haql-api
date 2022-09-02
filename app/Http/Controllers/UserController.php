<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update($user, $bio , $specialtiesIds , $username , $name){
        if ($user->cannot('update', $user)) {
            abort(403);
        }

        $user->update([
            'name' => $name,
            'username' => $username,
            'bio' => $bio,
        ]);

        $user->specialties()->sync($specialtiesIds);

        $user->save();

        return $user;
    }

    public function getUser($username){
        $user = User::where('username', $username)->first();

        return $user;
    }

    public function getUserSpecialties($username){
        $user = User::where('username', $username)->first();
        $specialties = $user->specialties()->paginate(10);

        return $specialties;
    }

    public function getUserProjects($username){
        $user = User::where('username', $username)->first();
        $projects = $user->projects()->paginate(10);
        return $projects;
    }

    public function getUserContributions($username){
        $user = User::where('username', $username)->first();
        $contributions = $user->contributions()->paginate(10);
        return $contributions;
    }
}
