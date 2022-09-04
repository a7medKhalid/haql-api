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

    public function test_get_latest_projects()
    {
        $this->seed();

        $response = $this->get('api/projects');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'owner_id',
                    'contributionsCount',
                    'issuesCount',
                ]
            ]
        ]);


    }

    public function test_get_trending_projects()
    {

        $response = $this->get('api/projects/trending');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'owner_id',
                    'contributionsCount',
                    'issuesCount',
                ]
            ]
        ]);
    }


    public function test_get_personal_projects()
        {

            $project = Project::first();
            $user = $project->owner;

            $this->actingAs($user);
            $response = $this->get('api/projects/personal');
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

    public function test_get_project()
    {

       $project = Project::first();

        $response = $this->get('api/projects/' . $project->id);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'id',
            'name',
            'description',
        ]);
    }

    //test_get_project_goals
    public function test_get_project_goals()
    {
        $goal = Goal::first();

        $project = $goal->project;
        $user = $project->owner;
        $response = $this->get('api/projects/' . $project->id . '/goals');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'tasks'
                ]
            ]
        ]);
    }

    //test_get_project_issues
    public function test_get_project_issues()
    {
        $issue = Issue::first();

        $project = $issue->project;
        $user = $project->owner;
        $response = $this->get('api/projects/' . $project->id . '/issues');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'status',
                    'issuerName',
                    'created_at',
                ]
            ]
        ]);
    }

    //test_get_project_contributions
    public function test_get_project_contributions()
    {
        $contribution = Contribution::first();

        $project = $contribution->project;
        $user = $project->owner;
        $response = $this->get('api/projects/' . $project->id . '/contributions');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'status',
                    'contributorName',
                ]
            ]
        ]);
    }


    //TODO: test_get_project_comments
//    //test_get_project_comments
//    public function test_get_project_comments()
//    {
//
//        $comment = Comment::where('commentedType', 'project')->first();
//        $project = Project::find($comment->commented_id);
//        $user = $project->owner;
//
//        $response = $this->get('api/projects/' . $project->id . '/comments');
//        $response->assertStatus(200);
//
//        $response->assertJsonStructure([
//            'comments' => [
//                'data' => [
//                    '*' => [
//                        'id',
//                        'title',
//                        'body',
//                        'commenterName',
//                        'commenterUsername',
//                        'user_id',
//                        'created_at',
//                    ]
//                ]
//            ]
//
//        ]);
//    }














}
