<?php

namespace App;

use Str;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public static function add($email)
    {
    	$sub = new self;
    	$sub->email = $email;
    	$sub->save();

    	return $sub;
    }

    public static function getActive()
    {
        return self::where('token', null)->get();
    }

    public function generateToken()
    {
        $this->token = Str::random(100);
        $this->save();
    }

    public function verify()
    {
        $this->token = null;
        $this->save();
    }

    public function remove()
    {
    	$this->delete();
    }

    public function getStatus()
    {
    	return $this->token == null ? 'Active' : 'Confirmation pending';
    }
}
