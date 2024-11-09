<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecaptchaController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/form',[RecaptchaController::class,'form']);
