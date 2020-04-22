<?php

namespace Tests\Unit;

use App\User;
use App\Post;
use App\Comment;
use App\Exceptions\BannedUserCannotCommentException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentModelTest extends TestCase
{
	use DatabaseMigrations;

    /**
     * @test
     */
    public function banned_user_cannot_comment()
    {
        $this->authenticate()->ban();

    	$post = factory(Post::class)->make(['id' => 10]);

    	$this->expectException(BannedUserCannotCommentException::class);

    	$data = [
    		'text' => 'sdmsd;sdmds',
    		'post_id' => $post->id,
    		'parent_id' => null
    	];

    	$comment = Comment::createNew($data);

    }
}
