<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function create($name){
        $specialty = Specialty::create(['name' => $name]);

        return $specialty;
    }
}
