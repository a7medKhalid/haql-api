<?php

namespace Tests\Feature;

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


}
