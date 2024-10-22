<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageCompressController;

Route::get('/', function () {
    return view('image');
});

Route::post('image-save',[ImageCompressController::class,'imageCompress']);