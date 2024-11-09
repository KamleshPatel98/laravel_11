<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecaptchaController extends Controller
{
    public function form(Request $request){
        $request->validate([
            'g-recaptcha-response' => 'recaptcha',
        ]);
        return $request;
    }
}
