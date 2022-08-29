<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SpecialtyController;
use Illuminate\Http\Request;

class SpecialtyAPIController extends Controller
{
    public function createSpecialty(Request $request){

        $request->validate([
            'name' => 'required|string',
        ]);

        $specialty_controller = new SpecialtyController;

        $specialty = $specialty_controller->create($request->name);
        return $specialty;
    }


}
