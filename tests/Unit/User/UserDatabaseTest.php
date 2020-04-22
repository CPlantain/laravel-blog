<?php

namespace Tests\Unit\User;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserDatabaseTest extends TestCase
{
	use DatabaseMigrations;

    private $user;
    
	public function setUp(): void
	{
		parent::setUp();

		$this->user = factory(User::class)->create();
	}

    /** @test */
    public function user_has_name()
    {
        $this->assertNotEmpty($this->user->name);
        $this->assertDatabaseHas('users', ['name' => $this->user->name]);
    }

    /** @test */
    public function user_has_email()
    {
        $this->assertNotEmpty($this->user->email);
        $this->assertDatabaseHas('users', ['email' => $this->user->email]);
    }

    /** @test */
    public function user_has_password()
    {
        $this->assertNotEmpty($this->user->password);
        $this->assertDatabaseHas('users', ['password' => $this->user->password]);
    }

    /** @test */
    public function user_has_description_field()
    {
        $this->assertDatabaseHas('users', ['description' => $this->user->description]);
    }

    /** @test */
    public function user_has_avatar_field()
    {
        $this->assertDatabaseHas('users', ['avatar' => $this->user->avatar]);
    }

    /** @test */
    public function user_has_status_field()
    {
        $this->assertDatabaseHas('users', ['status' => $this->user->status]);
    }

    /** @test */
    public function user_has_admin_field()
    {
        $this->assertDatabaseHas('users', ['is_admin' => $this->user->is_admin]);
    }
}
