<?php

namespace App\Http\Controllers\Admin;

use App\Subscription;
use App\Mail\BlogMailing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class MailingsController extends Controller
{
    public function write()
    {
    	return view('admin.mailings.write');
    }

    public function send(Request $request)
    {
    	$mailing = $request->get('mail_body');

    	$subscribers = Subscription::getActive();

    	Mail::to($subscribers)->send(new BlogMailing($mailing));

    	return redirect()->route('mailings.write')
    					->with('status', 'Your mailing has been sent!');
    }
}
