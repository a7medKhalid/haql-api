<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Contribution;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\MigrateFreshSeedOnce;
use Tests\TestCase;

class ContributionTest extends TestCase
{
    use MigrateFreshSeedOnce;

    public function testCreateContribution(){
        $user = User::factory()->create(['name' => 'ContributionMaker']);

        $projectOwner = User::factory()->create(['name' => 'ProjectOwner']);

        $project = Project::factory()->create([
            'name' => 'Test Project',
            'owner_id' => $projectOwner->id,

            ]);

        $response = $this->actingAs($user)->post('api/contributions', [
            'title' => 'ContributionTitle',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
            'project_id' => $project->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('contributions', [
            'title' => 'ContributionTitle',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
        ]);
    }

    public function testOwnerCanNotUpdateContribution(){
       $user = User::where('name', 'ContributionMaker')->first();

       $contribution = Contribution::where('title', 'ContributionTitle')->first();

        $response = $this->actingAs($user)->put('api/contributions/',[
        'contribution_id' => $contribution->id,
            'status' => 'accepted',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('contributions', [
            'title' => 'ContributionTitle',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
            'status' => 'open',
        ]);

    }

    public function testProjectOwnerCanUpdateContribution(){
        $user = User::where('name', 'ProjectOwner')->first();

        $contribution = Contribution::where('title', 'ContributionTitle')->first();

        $response = $this->actingAs($user)->put('api/contributions/',[
        'contribution_id' => $contribution->id,
            'status' => 'accepted'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('contributions', [
            'title' => 'ContributionTitle',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
            'status' => 'accepted',
        ]);
    }

    public function testCanNotDeleteAcceptedContribution(){
        $user = User::where('name', 'ContributionMaker')->first();

        $contribution = Contribution::where('title', 'ContributionTitle')->first();

        $response = $this->actingAs($user)->delete('api/contributions/',[
        'contribution_id' => $contribution->id,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('contributions', [
            'title' => 'ContributionTitle',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
            'status' => 'accepted',
        ]);
    }

    public function testDeleteContribution(){
        $user = User::factory()->create(['name' => 'ContributionMaker']);
        $project = Project::factory()->create([
            'name' => 'ContributionProject',
        ]);
        $contribution = Contribution::factory()->create([
            'title' => 'ContributionTitle ToDelete',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
            'project_id' => $project->id,
            'contributor_id' => $user->id,
        ]);
        $response = $this->actingAs($user)->delete('api/contributions/',[
            'contribution_id' => $contribution->id,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('contributions', [
            'title' => 'ContributionTitle ToDelete',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
        ]);


    }

    //test get contribution by id (title, description, link, status, project_id, contributor_id, contributor_name, created_at, )
    public function testGetContributionById(){
        $user = User::factory()->create(['name' => 'ContributionMaker']);
        $project = Project::factory()->create([
            'name' => 'ContributionProject',
        ]);
        $contribution = Contribution::factory()->create([
            'title' => 'ContributionTitle ToGet',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
            'project_id' => $project->id,
            'contributor_id' => $user->id,
        ]);
        $response = $this->actingAs($user)->get('api/contributions/'.$contribution->id);
        $response->assertStatus(200);

        $response->assertJsonStructure(
            [

                'id',
                'title',
                'description',
                'link',
                'status',
                'project_id',
                'contributor_id',
                'contributorName',
                'created_at',

            ]
        );
    }

    //test get contribution comments (id, title, body, commenterName, commenterUsername, user_id, created_at)
    public function test_get_contribution_comments(){
        $user = User::factory()->create(['name' => 'ContributionMaker']);
        $project = Project::factory()->create([
            'name' => 'ContributionProject',
        ]);
        $contribution = Contribution::factory()->create([
            'title' => 'ContributionTitle ToGet',
            'description' => 'ContributionDescription',
            'link' => 'ContributionLink',
            'project_id' => $project->id,
            'contributor_id' => $user->id,
        ]);
        $comments = Comment::factory()->count(3)->create([
            'commented_id' => $contribution->id,
            'commentedType' => 'contribution',
            'user_id' => $user->id,
        ]);
        $contribution->comments()->saveMany($comments);


        $response = $this->actingAs($user)->get('api/contributions/'.$contribution->id.'/comments');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'comments' => [
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'body',
                        'commenterName',
                        'commenterUsername',
                        'user_id',
                        'created_at',
                    ]
                ]
            ]

        ]);
    }


}
