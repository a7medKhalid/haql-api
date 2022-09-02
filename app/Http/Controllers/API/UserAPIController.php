<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAPIController extends Controller
{
    public function updateUser(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'bio' => 'required|string',
            'specialtiesIds' => 'required|array',
        ]);
        $user = Auth::user();

        $user_controller = new UserController;
        $user = $user_controller->update($user, $request->bio, $request->specialtiesIds, $request->username, $request->name);
        return $user;
    }

    public function getUser(Request $request, $username){
        $user_controller = new UserController;
        $user = $user_controller->getUser($username);
        return $user;
    }

    public function getUserProjects(Request $request, $username){
        $user_controller = new UserController;
        $projects = $user_controller->getUserProjects($username);
        return $projects;
    }

    public function getUserSpecialties(Request $request, $username){
        $user_controller = new UserController;
        $specialties = $user_controller->getUserSpecialties($username);
        return $specialties;
    }

    public function getUserContributions(Request $request, $username){
        $user_controller = new UserController;
        $contributions = $user_controller->getUserContributions($username);
        return $contributions;
    }
}
