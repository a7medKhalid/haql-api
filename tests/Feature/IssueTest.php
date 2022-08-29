<?php

namespace Tests\Feature;

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class IssueTest extends TestCase
{
    use MigrateFreshSeedOnce;

    //test creating issue
    public function test_create_issue(){
        $user = User::factory()->create(['name' => 'issueMaker']);
        $projectMaker = User::factory()->create(['name' => 'issueProjectMaker']);

        $this->actingAs($user);

        $project = Project::factory()->create(['name' => 'Test Project', 'description' => 'Test Description', 'owner_id' => $projectMaker->id]);

        $response = $this->json('POST', '/api/issues', [
            'title' => 'Test Issue',
            'body' => 'Test Description',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'title' => 'Test Issue',
            'description' => 'Test Description',
        ]);

        $this->assertDatabaseHas('issues', [
            'title' => 'Test Issue',
            'description' => 'Test Description',
        ]);
    }

    public function test_issue_maker_can_not_update_issue_status(){
        $user = User::where(['name' => 'issueMaker'])->first();

        $this->actingAs($user);

        $issue = Issue::first();

        $response = $this->json('PUT', '/api/issues/' , [
            'issue_id' => $issue->id,
            'status' => 'closed',
        ]);

        $response->assertStatus(403);


//        $response->assertJson([
//            'title' => 'Test Issue',
//            'description' => 'Test Description',
//            'status' => 'open',
//        ]);

        $this->assertDatabaseHas('issues', [
            'title' => 'Test Issue',
            'description' => 'Test Description',
            'status' => 'open',
        ]);
    }

    public function test_project_owner_can_update_issue_status(){
        $user = User::where(['name' => 'issueProjectMaker'])->first();
        $this->actingAs($user);
        $issue = Issue::first();
        $response = $this->json('PUT', '/api/issues/' , [
            'issue_id' => $issue->id,
            'status' => 'closed',
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'title' => 'Test Issue',
            'description' => 'Test Description',
            'status' => 'closed',
        ]);
        $this->assertDatabaseHas('issues', [
            'title' => 'Test Issue',
            'description' => 'Test Description',
            'status' => 'closed',
        ]);
    }



}
