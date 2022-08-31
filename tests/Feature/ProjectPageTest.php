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
            $response = $this->get('api/projects/personal');
            $response->assertStatus(200);
        }

    public function test_get_project()
    {

       $project = Project::first();
       $user = $project->owner;

        $response = $this->get('api/projects/' . $user->username . '/' . $project->name);
        $response->assertStatus(200);
    }

    //test_get_project_goals
    public function test_get_project_goals()
    {
        $goal = Goal::first();

        $project = $goal->project;
        $user = $project->owner;
        $response = $this->get('api/projects/' . $user->username . '/' . $project->name . '/goals');
        $response->assertStatus(200);
    }

    //test_get_project_issues
    public function test_get_project_issues()
    {
        $issue = Issue::first();

        $project = $issue->project;
        $user = $project->owner;
        $response = $this->get('api/projects/' . $user->username . '/' . $project->name . '/issues');
        $response->assertStatus(200);
    }

    //test_get_project_contributions
    public function test_get_project_contributions()
    {
        $contribution = Contribution::first();

        $project = $contribution->project;
        $user = $project->owner;
        $response = $this->get('api/projects/' . $user->username . '/' . $project->name . '/contributions');
        $response->assertStatus(200);
    }

    //test_get_project_comments
    public function test_get_project_comments()
    {

        $comment = Comment::where('commentedType', 'project')->first();
        $project = Project::find($comment->commented_id);
        $user = $project->owner;

        $response = $this->get('api/projects/' . $user->username . '/' . $project->name . '/comments');
        $response->assertStatus(200);
    }














}
