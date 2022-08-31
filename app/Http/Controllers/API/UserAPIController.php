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
}
