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
}
