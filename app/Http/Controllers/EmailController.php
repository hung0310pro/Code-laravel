<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendMailRegisteredUser;

class EmailController extends Controller
{
    public function sendEMail(){
    	$detail = [
    		'title' => 'Title test 123',
		    'body' => 'Body email test'
	    ];

    	\Mail::to('hung0210pro@gmail.com')->send(new SendMailRegisteredUser($detail));
    	return view('emailsregistered');
    }
}
