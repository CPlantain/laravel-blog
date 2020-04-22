<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use App\Comment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    private $post;

    public function setup(): void
    {
        parent::setUp();
        $this->post = factory(Post::class)->create();
    }

    /** @test */
    public function user_can_see_posts()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($this->post->title);
    }

    /** @test */
    public function user_can_see_a_single_post()
    {
        $response = $this->get('/post/' . $this->post->slug);
        $response->assertStatus(200);
        $response->assertSee($this->post->title);
        $response->assertSee($this->post->content);
    }

    /** @test */
    public function logged_user_can_comment_on_post()
    {
        $this->authenticate();

        $data = [
            'text' => 'everevere',
            'post_id' => '20',
            'parent_id' => null,
        ];

        $response = $this->post('/comment', $data);

        $this->assertDatabaseHas('comments', $data);
    }
}
