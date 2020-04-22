<?php

namespace App;

use App\Exceptions\User as Exc;
use Illuminate\Support\Facades\Auth;

trait UserAuth
{
    public static function register($data)
    {   
        if(Auth::check() && !Auth::user()->isAdmin()){
            throw new Exc\UserAlreadyLoginedException('You can not register while being logged in');  
        }

        $user = self::add($data);
        $user->generatePassword($data['password']);
        return $user;
    }

    private static function add($fields)
    {
        $user = new self;
        $user->fill($fields);
        $user->save();

        return $user;
    }

    private function generatePassword($password)
    {
        if ($password != null) {
            $this->password = bcrypt($password);
            $this->save();
        }
    }

    public static function login($data)
    {
        if (Auth::check()) {
            throw new Exc\UserAlreadyLoginedException('You can not login while being already logged in');
        } else if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password']
        ])) {
            throw new Exc\WrongEmailOrPasswordException('Wrong email or password!');
        }

        return true;
    }

    public static function logout()
    {
        if (!(Auth::check())) {
            throw new Exc\UserIsNotLoginedException('You can not logout if you are not logged in');
        }

        Auth::logout();

        return true;
    }
}