<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function captchaForm()
    {
        return view('captchaform');
    }
    public function storeCaptchaForm(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required',
            'mobile_number' => 'required',
            'g-recaptcha-response' => ['required', new \App\Rules\ValidRecaptcha],
        ]);

        dd('successfully validate');
    }
}
