<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
    	$user = Auth::user();
    	return view('pages.profile', compact('user'));
    }

    public function update(Request $request)
    {
    	$user = Auth::user();

    	$this->validate($request, [
    		'name' => 'required',
    		'email' => [
    			'required', 
    			'email', 
    			Rule::unique('users')->ignore($user->id)
    		],
    		'avatar' => 'nullable|image'
    	]);

    	$user->edit($request->all());
    	$user->generatePassword($request->get('password'));
    	$user->uploadAvatar($request->file('avatar'));

    	return redirect()->back()->with('status', 'Your profile was updated');
    }
}
