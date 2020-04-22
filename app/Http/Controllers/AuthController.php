<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Exceptions\User as Exc;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registerForm()
    {
    	return view('pages.register');
    }

    public function register(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required',
    		'email' => 'required|email|unique:users',
    		'password' => 'required'
    	]);

        try {
            $user = User::register($request->all());
        } catch (Exc\UserAlreadyLoginedException $e) {

            return redirect('/')->with('status', $e->getMessage());
        }

    	return redirect('/login', 201);
    }

    public function loginForm()
    {
    	return view('pages.login');	
    }

    public function login(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'required|email',
    		'password' => 'required'
    	]);

        try {
            User::login($request->all());
        } catch (Exc\UserAlreadyLoginedException $e) {

            return redirect('/')->with('status', $e->getMessage());

        } catch (Exc\WrongEmailOrPasswordException $e) {

            return redirect('/login')->with('status', $e->getMessage());
        } 

    	return redirect('/', 201);
    }

    public function logout()
    {
    	try {
            User::logout();
        } catch (Exc\UserIsNotLoginedException $e) {
            
            return redirect('/')->with('status', $e->getMessage());
        }

    	return redirect('/', 201);
    }
}
