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
            'bio' => 'nullable|string',
            'specialtiesIds' => 'nullable|array',
        ]);
        $user = Auth::user();

        $user_controller = new UserController;
        $user = $user_controller->update($user, $request->bio, $request->specialtiesIds);
        return $user;
    }
}
