<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\Mail\SubscribeEmail;
use Illuminate\Http\Request;

class SubsController extends Controller
{
    public function subscribe(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'required|email|unique:subscriptions'
    	]);

    	$subscriber = Subscription::add($request->get('email'));
        $subscriber->generateToken();

    	\Mail::to($subscriber)->send(new SubscribeEmail($subscriber));

    	return redirect()->back()->with('status', 'Thank you! Now check your email!');
    }

    public function verify($token)
    {
    	$subscriber = Subscription::where('token', $token)->firstOrFail();
    	$subscriber->verify();

    	return redirect()->route('main')->with('status', 'Your subscription was verified!');
    }
}
