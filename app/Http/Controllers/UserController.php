<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(User $authUser, User $user, $bio = null, $specialtiesIds = null ){
        if ($authUser->cannot('update', $user)) {
            abort(403);
        }

        $user->update([
            'bio' => $bio,
        ]);

        $user->specialties()->sync($specialtiesIds);
    }
}
