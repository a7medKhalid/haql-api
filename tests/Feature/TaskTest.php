<?php

namespace Tests\Feature;

use App\Models\Goal;
use App\Models\Project;
use App\Models\Specialty;
use App\Models\Task;
use App\Models\User;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use MigrateFreshSeedOnce;

    public function test_create_Task()
    {
        $user = User::factory()->create(['name' => 'taskMaker']);

        $this->actingAs($user);

        $project = Project::factory()->create(['owner_id' => $user->id]);

        $goal = Goal::factory()->create(['title' => 'Goal Task', 'project_id' => $project->id]);

        $specialties = Specialty::factory(3)->create();

        $specialtiesIDS = $specialties->pluck('id')->toArray();

        $response = $this->json('POST', '/api/tasks', [
            'title' => 'Test Task',
            'goal_id' => $goal->id,
            'specialtiesIds' => $specialtiesIDS,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'title' => 'Test Task',
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
        ]);

        $this->assertDatabaseHas('task_has_specialties', [
            'specialty_id' => $specialtiesIDS[0],
        ]);

        $this->assertDatabaseHas('task_has_specialties', [
            'specialty_id' => $specialtiesIDS[1],
        ]);

        $this->assertDatabaseHas('task_has_specialties', [
            'specialty_id' => $specialtiesIDS[2],
        ]);
    }

    public function test_update_task()
    {
        $user = User::where('name', 'taskMaker')->first();

        $this->actingAs($user);

        $task = Task::where('title', 'Test Task')->first();
        $response = $this->json('PUT', '/api/tasks/', [
            'isCompleted' => true,
            'task_id' => $task->id,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'title' => 'Test Task',
            'isCompleted' => true,
        ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'isCompleted' => true,
        ]);
    }

    public function test_delete_task()
    {
        $user = User::where('name', 'taskMaker')->first();

        $this->actingAs($user);
        $task = Task::where('title', 'Test Task')->first();
        $response = $this->json('DELETE', '/api/tasks/', [
            'task_id' => $task->id,
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'title' => 'Test Task',
        ]);
    }
}
