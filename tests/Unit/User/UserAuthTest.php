<?php

namespace Tests\Unit\User;

use App\User;
use App\Exceptions\User as Exc;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group auth
 */
class UserAuthTest extends TestCase
{
    private $data;

    /** setting up test data for user */
	public function setup(): void
	{
		parent::setUp();

        $this->data = [
            'name' => 'John',
            'email' => 'john@test.com',
            'password' => 'secret',
        ];
	}

    /** @test */
    public function user_registration()
    {
        $user = User::register($this->data);

        $this->assertEquals($user->name, $this->data['name']);
        $this->assertEquals($user->email, $this->data['email']);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password
        ]);

        return $user;
    }

    /** 
    * @test 
    * @depends user_registration
    */
    public function user_password_is_hashed($user)
    {
        $this->assertNotEquals($user->password, $this->data['password']);
        $this->assertTrue(
            password_verify($this->data['password'], $user->password)
        );
    }

    /** 
    * @test
    * @depends user_registration
    */
    public function user_email_is_formatted($user)
    {
       $this->assertNotFalse(filter_var($user->email, FILTER_VALIDATE_EMAIL));
    }

    /** @test */
    public function cannot_register_if_user_already_logined_exception()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->expectException(Exc\UserAlreadyLoginedException::class);
        $this->expectExceptionMessage('You can not register while being logged in');
        
        User::register($this->data);    
    }

    /** @test */
    public function user_login()
    {
    	$user = User::register($this->data);

    	User::login($this->data);

    	$this->assertTrue(Auth::check());
    	$this->assertEquals(Auth::id(), $user->id);
    }

    /** @test */
    public function cannot_login_with_wrond_password_or_email_exception()
    {
    	$user = User::register($this->data);

    	$wrong_data = [
    		'email' => 'wrong@email.com', 
    		'password' => 'wrong'
    	];

    	$this->expectException(Exc\WrongEmailOrPasswordException::class);
    	$this->expectExceptionMessage('Wrong email or password!');

    	User::login($wrong_data);
    }

    /** @test */
    public function cannot_login_if_user_already_logined_exception()
    {
    	$user = factory(User::class)->create();

    	$this->actingAs($user);

    	$this->expectException(Exc\UserAlreadyLoginedException::class);
    	$this->expectExceptionMessage('You can not login while being already logged in');

    	User::login($this->data);
    }

    /** @test */
    public function user_logout()
    {
    	$user = factory(User::class)->create();

    	$this->actingAs($user);

    	User::logout();

    	$this->assertFalse(Auth::check());
    }

    /** @test */
    public function cannot_logout_if_user_not_logined_exception()
    {
    	$user = factory(User::class)->create();

    	$this->expectException(Exc\UserIsNotLoginedException::class);
    	$this->expectExceptionMessage('You can not logout if you are not logged in');

    	User::logout();
    }
}
