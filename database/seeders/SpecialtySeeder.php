<?php

namespace Database\Seeders;

use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialties = Specialty::factory(10)->create();

        foreach ($specialties as $specialty) {
            //get random user
            $user = User::inRandomOrder()->first();

            //attach specialty to user
            $user->specialties()->attach($specialty);
        }


    }
}
