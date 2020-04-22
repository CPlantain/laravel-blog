<?php

namespace Tests\Unit\User;

use App\User;
use App\Post;
use App\Comment;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRelationsTest extends TestCase
{
	use DatabaseMigrations;

	private $user;

	public function setUp(): void
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
	}

    /** @test */
    public function user_with_posts_relations()
    {
    	// создать пост, привязанный к id этого пользователя
    	$post = factory(Post::class)->create(['user_id' => $this->user->id]);

    	// проверить, что метод $user->posts->id возвращает нужный id
    	foreach ($this->user->posts as $user_post) {
    		$this->assertEquals($user_post->id, $post->id);  
    	}

    	// проверить, что коллекция постов не пуста
    	$this->assertNotEmpty($this->user->posts);
    }

    /** @test */
    public function user_with_comments_relations()
    {
    	// создать комментарий, привязанный к id этого пользователя
    	$comment = factory(Comment::class)->create(['user_id' => $this->user->id]);

    	// проверить, что метод $user->comments->id возвращает нужный id
    	foreach ($this->user->comments as $user_comment) {
    		$this->assertEquals($user_comment->id, $comment->id);  
    	}

    	// проверить, что коллекция комментариев не пуста
    	$this->assertNotEmpty($this->user->comments);    	
    }

    /** @test */
    public function posts_relations_cleared_when_user_deleted()
    {
    	// создать 3 поста, привязанных к id этого пользователя
    	$posts = factory(Post::class, 3)->create(['user_id' => $this->user->id]);

    	// удалить пользователя
    	$this->user->remove();

    	// проверить, что в user_id всех трёх постов стоит null
    	foreach ($posts as $post) {
      		$this->assertDatabaseHas('posts', ['id' => $post->id, 'user_id' => null]);
      		$this->assertNull($post->author);
    	}
    }

    /** @test */
    public function related_comments_deleted_when_user_deleted()
    {
    	// создать 3 комментария, привязанных к этому пользователю
    	$comments = factory(Comment::class, 3)->create(['user_id' => $this->user->id]);

    	// удалить пользователя
    	$this->user->remove();

    	// проверить, что в базе данных нет ни одного из этих комментариев
    	foreach ($comments as $comment) {
    		$this->assertDatabaseMissing('comments', 
    			['id' => $comment->id, 'user_id' => $this->user->id]
    		);
    	}
    }
}
