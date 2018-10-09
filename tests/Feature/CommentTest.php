<?php

namespace Tests\Feature;

use App\Comment;
use App\Podcast;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * It creating new comment
     *
     * @return void
     */
    public function testCreateNewComment()
    {
        $podcast = factory(Podcast::class)->create([
            'name' => 'test name',
            'description' => 'test description',
            'feed_url' => 'http://feed-url.com'
        ]);
        $response = $this->post("api/comment/{$podcast->id}", [
            'author_name' => 'John Doe',
            'author_email' => 'john@mail.com',
            'comment' => 'Test comment'
        ]);
        $response->assertStatus(200);
        $this->assertEquals(1, Comment::all()->count());
        $this->assertEquals(1, Podcast::find($podcast->id)->comments->count());
    }

    /**
     * It setting flag to existing comment by given id
     *
     * @return void
     */
    public function testSetFlagToCurrentCommentById()
    {
        $podcast = factory(Podcast::class)->create([
            'name' => 'test name',
            'description' => 'test description',
            'feed_url' => 'http://feed-url.com'
        ]);
        $comment = $podcast->comments()->create([
            'author_name' => 'John Doe',
            'author_email' => 'john@mail.com',
            'comment' => 'Test comment'
        ]);
        $response = $this->post("api/flag-comment/{$comment->id}");
        $response->assertStatus(200);
        $this->assertNotNull(Comment::withTrashed()->find($comment->id)->deleted_at);
    }
}
