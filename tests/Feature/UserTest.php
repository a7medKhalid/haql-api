<?php

namespace Tests\Feature;

use App\Models\Contribution;
use App\Models\Project;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class UserTest extends TestCase
{

    use MigrateFreshSeedOnce;

    //TODO: test get latest users and most contributors and most projects and all users (name, username, projectsCount, IssuesCount, contributionsCount)

    public function test_get_latest_users()
    {
        $this->seed();

        $response = $this->get('api/users');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'username',
                    'projectsCount',
                    'issuesCount',
                    'contributionsCount',
                ]
            ]
        ]);
    }

    public function test_get_most_contributors()
    {
        $this->seed();

        $response = $this->get('api/users/most-contributors');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'username',
                    'projectsCount',
                    'issuesCount',
                    'contributionsCount',
                ]
            ]
        ]);
    }

    public function test_get_most_projects()
    {
        $this->seed();

        $response = $this->get('api/users/most-projects');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'username',
                    'projectsCount',
                    'issuesCount',
                    'contributionsCount',
                ]
            ]
        ]);
    }

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

        $response->assertJsonStructure([
            'id',
            'name',
            'username',
            'bio',
        ]);

    }

    public function test_get_user_specialties()
    {
        $user = User::factory()->create(['name' => 'user']);
        $specs = Specialty::factory(10)->create(['name' => 'specialty']);
        $user->specialties()->attach($specs->pluck('id'));
        $this->actingAs($user);
        $response = $this->get('/api/users/' . $user->username . '/specialties');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ]
            ]
        ]);

    }

    public function test_get_user_projects()
    {
        $user = User::factory()->create(['name' => 'user']);
        Project::factory(10)->create(['owner_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->get('/api/users/' . $user->username . '/projects');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'contributionsCount',
                    'issuesCount',
                ]
            ]
        ]);
    }

    public function test_get_user_contributions()
    {
        $user = User::factory()->create(['name' => 'user']);
        Contribution::factory(15)->create(['contributor_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->get('/api/users/' . $user->username . '/contributions');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'projectName',
                    'project_id',
                ]
            ]
        ]);

    }

}
