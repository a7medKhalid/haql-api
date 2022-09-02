<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class GoalTest extends TestCase
{

    use MigrateFreshSeedOnce;

    public function test_create_goal()
    {
        $user = User::factory()->create(['name' => 'goalMaker']);

        $this->actingAs($user);

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $response = $this->json('POST', '/api/goals', [
            'title' => 'Test Goal',
            'description' => 'Test Description',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'title' => 'Test Goal',
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseHas('goals', [
            'title' => 'Test Goal',
            'description' => 'Test Description',
        ]);
    }

    public function test_update_goal(){
        $user = User::where('name' , 'goalMaker')->first();

        $this->actingAs($user);

        $goal = Goal::where('title' , 'Test Goal')->first();

        $response = $this->json('PUT', '/api/goals/' , [
            'isCompleted' => true,

            'goal_id' => $goal->id,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'title' => 'Test Goal',
            'description' => 'Test Description',
            'isCompleted' => true,
        ]);

        $this->assertDatabaseHas('goals', [
            'title' => 'Test Goal',
            'description' => 'Test Description',
            'isCompleted' => true,
        ]);
    }

    public function test_delete_goal(){
        $user = User::where('name' , 'goalMaker')->first();

        $this->actingAs($user);

        $goal = Goal::where('title' , 'Test Goal')->first();

        $response = $this->json('DELETE', '/api/goals/' , [
            'goal_id' => $goal->id,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'title' => 'Test Goal',
            'description' => 'Test Description',
            'isCompleted' => true,
        ]);

        $this->assertDatabaseMissing('goals', [
            'title' => 'Test Goal',
            'description' => 'Test Description',
            'isCompleted' => true,
        ]);
    }

    public function test_get_goal()
    {
        $this->seed();

        $goal = Goal::first();

        $response = $this->get('api/goals/' . $goal->id);
        $response->assertStatus(200);
    }

//    public function test_get_goals()
//    {
//
//        $this->seed();
//        // TOD: fix test
//        $user = User::first();
//        $project = Project::factory()->create(['owner_id' => $user->id]);
//
//        $response = $this->call('GET', 'api/goals', [
//            'username' => $user->username,
//            'projectName' => $project->name,
//        ]);
//
//        $response->assertStatus(200);
//    }


}
