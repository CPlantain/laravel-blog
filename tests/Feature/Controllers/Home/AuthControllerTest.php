<?php

namespace Tests\Feature\Controllers\Home;

use App\User;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @group auth
 */
class AuthControllerTest extends TestCase
{
    private $user_data;

    /** setting up test data for user */
    public function setUp(): void
    {
        parent::setUp();

        $this->user_data = [
            'name' => 'John',
            'email' => 'test@email.com',
            'password' => 'secret'
        ];
    }

    /** @test */
    public function register_form_display()
    {
        $response = $this->get('/register');

        $response->assertOk()
                ->assertSeeInOrder(['Register', 'Name', 'Email', 'Password']);
    }

    /** @test */
    public function user_can_register()
    {
        $response = $this->post('/register', $this->user_data);

        $response->assertCreated()
            ->assertRedirect('/login');

        $this->assertDatabaseHas('users', [
            'email' => $this->user_data['email'], 
            'name' => $this->user_data['name']
        ]);

        $user = User::where('email', $this->user_data['email'])->firstOrFail();

        $this->assertNotEmpty($user->password);
        $this->assertNotEquals($user->password, $this->user_data['password']);
        $this->assertTrue(password_verify($this->user_data['password'], $user->password));
    }

    /** @test */
    public function cannot_register_if_user_already_logined()
    {   
        $this->withoutMiddleware();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
                        ->post('/register', $this->user_data);
        $response
            ->assertStatus(302)
            ->assertRedirect('/')
            ->assertSessionHas('status', 'You can not register while being logged in');

        $this->assertDatabaseMissing('users', ['email' => $this->user_data['email']]);
    }

    /** @test */
    public function login_form_display()
    {
        $response = $this->get('/login');

        $response->assertOk()
                ->assertSeeInOrder(['Login', 'Email', 'Password']);   
    }

    /** @test */
    public function user_can_login()
    {
        $user = User::register($this->user_data);

        $response = $this->post('/login', $this->user_data);

        $response->assertStatus(201)
                ->assertLocation('/')
                ->assertSessionMissing('status');

        $this->assertTrue(Auth::check());
        $this->assertEquals(Auth::id(), $user->id);
    }

    /** @test */
    public function cannot_login_with_wrond_password_or_email()
    {
        $user = User::register($this->user_data);

        $wrong_data = [
            'email' => 'wrong@email.com', 
            'password' => 'wrong'
        ];

        $response = $this->post('/login', $wrong_data);

        $response->assertRedirect('/login')
                ->assertSessionHas('status', 'Wrong email or password!');

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function cannot_login_if_user_already_logined()
    {
        $this->withoutMiddleware();

        $user = User::register($this->user_data);

        $this->actingAs($user);
        $current_session = session()->getId();

        $response = $this->post('/login', $this->user_data);

        $response->assertRedirect('/')
                ->assertSessionHas('status', 'You can not login while being already logged in');

        $this->assertEquals(session()->getId(), $current_session);
    }

    /** @test */
    public function user_can_logout()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $response = $this->get('/logout');

        $response->assertStatus(201)
                ->assertRedirect('/')
                ->assertSessionMissing('status');

        $this->assertFalse(Auth::check());
    }

    /** @test */
    public function cannot_logout_if_user_not_logined()
    {
        $this->withoutMiddleware();

        $user = factory(User::class)->create();

        $response = $this->get('/logout');

        $response->assertStatus(302)
                ->assertRedirect('/')
                ->assertSessionHas('status', 'You can not logout if you are not logged in');
    }
}
