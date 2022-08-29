<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update($user, $bio = null, $specialtiesIds = null ){
        if ($user->cannot('update', $user)) {
            abort(403);
        }

        $user->update([
            'bio' => $bio,
        ]);

        $user->specialties()->sync($specialtiesIds);

        $user->save();

        return $user;
    }
}
