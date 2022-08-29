<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class UserTest extends TestCase
{

    use MigrateFreshSeedOnce;

    public function test_update_user()
    {
        $user = User::factory()->create(['name' => 'user']);
        $this->actingAs($user);
        $response = $this->json('PUT', '/api/users' , [
            'bio' => 'Test Bio',
            'specialtiesIds' => [1,2],
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'bio' => 'Test Bio',
        ]);
        $this->assertDatabaseHas('users', [
            'bio' => 'Test Bio',
        ]);
    }

}
