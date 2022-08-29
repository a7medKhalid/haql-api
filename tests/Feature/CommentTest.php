<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{

    public function test_create_comment()
    {
        $user = User::factory()->create(['name' => 'commentMaker']);
        $this->actingAs($user);
        $response = $this->json('POST', '/api/comments', [
            'title' => 'Test Comment',
            'body' => 'Test Comment Body',
            'commentedType' => 'project',
            'commented_id' => 1,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'title' => 'Test Comment',
            'body' => 'Test Comment Body',
        ]);
        $this->assertDatabaseHas('comments', [
            'title' => 'Test Comment',
            'body' => 'Test Comment Body',
        ]);
    }

    public function test_update_comment()
    {
        $user = User::where('name', 'commentMaker')->first();

        $this->actingAs($user);
        $comment = Comment::where('title', 'Test Comment')->first();
        $response = $this->json('PUT', '/api/comments/' , [
            'title' => 'Test Comment Updated',
            'body' => 'Test Comment Body Updated',
            'comment_id' => $comment->id,
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'title' => 'Test Comment Updated',
            'body' => 'Test Comment Body Updated',
        ]);
        $this->assertDatabaseHas('comments', [
            'title' => 'Test Comment Updated',
            'body' => 'Test Comment Body Updated',
        ]);
    }

    public function test_delete_comment()
    {
        $user = User::where('name', 'commentMaker')->first();
        $this->actingAs($user);
        $comment = Comment::where('title', 'Test Comment Updated')->first();
        $response = $this->json('DELETE', '/api/comments/' , [
            'comment_id' => $comment->id,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('comments', [
            'title' => 'Test Comment Updated',
            'body' => 'Test Comment Body Updated',
        ]);
    }


}
