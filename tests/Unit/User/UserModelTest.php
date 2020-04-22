<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\File;
use Tests\TestCase;


class UserModelTest extends TestCase
{

 //    private $data;

	// public function setup(): void
	// {
	// 	parent::setUp();

 //        $this->data = [
 //            'name' => 'John',
 //            'email' => 'john@test.com',
 //            'password' => 'secret',
 //        ];
	// }


    /** @test */
    public function user_can_upload_avatar()
    {
        // фейковый пользователь
        $user = factory(User::class)->create();

        // фейковая директория и фейковый файл
        Storage::fake('local');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $avatar = $user->uploadAvatar($file);

        // проверяем, что файл загружен
        Storage::disk('local')->assertExists('uploads/' . $avatar);

        // проверяем, что имя файла сохранилось в модель и в базу
        $this->assertEquals($user->avatar, $avatar);
        $this->assertDatabaseHas('users', ['avatar' => $avatar]);

        return $user;
    }

    /** @test */
    public function uploaded_avatar_has_new_name()
    {
        // фейковый пользователь
        $user = factory(User::class)->create();

        // фейковая директория и фейковый файл
        Storage::fake('local');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $avatar = $user->uploadAvatar($file);

        $this->assertNotEquals($avatar, 'avatar.jpg');
    }

    /** @test */
    public function old_avatar_deleted_when_new_uploaded()
    {
        // фейковый пользователь с аватаркой
        $user = factory(User::class)->create(['avatar' => 'old_avatar.jpg']);

        // фейковая директория
        Storage::fake('local');

        // cохраняем рандомный файл в качестве тестовой картинки
        Storage::putFileAs('uploads', 
            new File('public/img/default-50x50.gif'), 
            'old_avatar.jpg');

        // проверяем, что всё сохранилось
        Storage::disk('local')->assertExists('uploads/old_avatar.jpg');

        // фейковая новая картинка
        $file = UploadedFile::fake()->image('avatar.jpg');

        $avatar = $user->uploadAvatar($file);

        // проверяем, что старого файла нет ни в директории, ни в модели, ни в базе
        Storage::disk('local')->assertMissing('uploads/old_avatar.jpg');
        $this->assertNotEquals($user->avatar, 'old_avatar.jpg');
        $this->assertDatabaseMissing('users', ['avatar' => 'old_avatar.jpg']);
    }

    /** 
    * @test
    * @depends user_can_upload_avatar
    */
    public function can_get_user_avatar($user)
    {
        $this->assertEquals('/uploads/' . $user->avatar, $user->getAvatar());
    }

    /** @test */
    public function default_image_returned_if_user_has_no_avatar()
    {
        $user = factory(User::class)->create(['avatar' => null]);

        $this->assertEquals('/img/default-50x50.gif', $user->getAvatar());
    }   
}
