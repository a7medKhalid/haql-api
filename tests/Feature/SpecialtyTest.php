<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class SpecialtyTest extends TestCase
{
    use MigrateFreshSeedOnce;

    public function testCreateSpecialty()
    {
        $user = User::factory()->create(['name' => 'specialtyMaker']);

        $this->actingAs($user);

        $response = $this->json('POST', '/api/specialties', [
            'name' => 'Test Specialty',
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'name' => 'Test Specialty',
        ]);

        $this->assertDatabaseHas('specialties', [
            'name' => 'Test Specialty',
        ]);
    }
}
