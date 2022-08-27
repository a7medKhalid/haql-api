<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use MigrateFreshSeedOnce;


    public function testCreateProject()
    {


        $user = User::factory()->create(['name' => 'projectMaker']);

        $this->actingAs($user);

        $response = $this->json('POST', '/api/projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
            'license' => 'Test License',
            'license_url' => 'Test License URL',
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);
    }

    //test non owner can not update project
    public function testNonOwnerCanNotUpdateProject()
    {
        $user = User::factory()->create(['name' => 'NotProjectMaker']);

        $this->actingAs($user);

        $project = Project::first();

        $response = $this->json('PUT', '/api/projects/' , [
            'name' => 'Test Project Updated',
            'description' => 'Test Description Updated',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(403);

        $response->assertJson([
            'message' => 'You are not authorized to perform this action.',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);
    }

    public function testUpdateProject()
    {
        $user = User::where('name', 'projectMaker')->first();

        $this->actingAs($user);

        $project = Project::first();

        $response = $this->json('PUT', '/api/projects/' , [
            'name' => 'Test Project Updated',
            'description' => 'Test Description Updated',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Test Project Updated',
            'description' => 'Test Description Updated',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project Updated',
            'description' => 'Test Description Updated',
        ]);
    }

    //test non owner can not delete project
    public function testNonOwnerCanNotDeleteProject()
    {
        $user = User::where('name', 'NotProjectMaker')->first();

        $this->actingAs($user);

        $project = Project::first();

        $response = $this->json('DELETE', '/api/projects/' , [
            'project_id' => $project->id,
        ]);

        $response->assertStatus(403);

        $response->assertJson([
            'message' => 'You are not authorized to perform this action.',
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project Updated',
            'description' => 'Test Description Updated',
        ]);
    }

    public function testDeleteProject()
    {
        $user = User::where('name', 'projectMaker')->first();

        $this->actingAs($user);

        $project = Project::first();

        $response = $this->json('DELETE', '/api/projects/', [
            'project_id' => $project->id,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'name' => 'Test Project Updated',
            'description' => 'Test Description Updated',
        ]);

        $this->assertDatabaseMissing('projects', [
            'name' => 'Test Project Updated',
            'description' => 'Test Description Updated',
        ]);
    }






}
