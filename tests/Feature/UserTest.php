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
            'username' => 'Test Username',
            'name' => 'Test Name',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'bio' => 'Test Bio',
        ]);
        $this->assertDatabaseHas('users', [
            'bio' => 'Test Bio',
        ]);
    }

    public function test_get_user()
    {
        $this->seed();

        $user = User::factory()->create(['name' => 'user']);
        $this->actingAs($user);
        $response = $this->get('/api/users/' . $user->username);
        $response->assertStatus(200);

    }

    public function test_get_user_specialties()
    {
        $user = User::factory()->create(['name' => 'user']);
        $this->actingAs($user);
        $response = $this->get('/api/users/' . $user->username . '/specialties');
        $response->assertStatus(200);

    }

    public function test_get_user_projects()
    {
        $user = User::factory()->create(['name' => 'user']);
        $this->actingAs($user);
        $response = $this->get('/api/users/' . $user->username . '/projects');
        $response->assertStatus(200);

    }

    public function test_get_user_contributions()
    {
        $user = User::factory()->create(['name' => 'user']);
        $this->actingAs($user);
        $response = $this->get('/api/users/' . $user->username . '/contributions');
        $response->assertStatus(200);

    }

}
