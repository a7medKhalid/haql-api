<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Contribution;
use App\Models\Goal;
use App\Models\Issue;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class ProjectPageTest extends TestCase
{
    use MigrateFreshSeedOnce;

    public function test_get_projects()
    {
        $this->seed();

        $response = $this->get('api/projects');
        $response->assertStatus(200);
    }
    public function test_get_personal_projects()
        {

            $project = Project::first();
            $user = $project->owner;

            $this->actingAs($user);
            $response = $this->get('api/projects/personal' . $project->id);
            $response->assertStatus(200);
        }

    public function test_get_project()
    {

       $project = Project::first();

        $response = $this->get('api/projects/' . $project->id);
        $response->assertStatus(200);
    }

    //test_get_project_goals
    public function test_get_project_goals()
    {
        $goal = Goal::first();

        $project = $goal->project;
        $response = $this->get('api/projects/' . $project->id . '/goals');
        $response->assertStatus(200);
    }

    //test_get_project_issues
    public function test_get_project_issues()
    {
        $issue = Issue::first();

        $project = $issue->project;
        $response = $this->get('api/projects/' . $project->id . '/issues');
        $response->assertStatus(200);
    }

    //test_get_project_contributions
    public function test_get_project_contributions()
    {
        $contribution = Contribution::first();

        $project = $contribution->project;
        $response = $this->get('api/projects/' . $project->id . '/contributions');
        $response->assertStatus(200);
    }

    //test_get_project_comments
    public function test_get_project_comments()
    {

        $comment = Comment::where('commentedType', 'project')->first();
        $response = $this->get('api/projects/' . $comment->commented_id . '/comments');
        $response->assertStatus(200);
    }














}
