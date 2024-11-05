<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerAuthController;

Route::get('login',function(){
    return response()->json(['status'=>false,'errors'=>'Unathorized'],500);
})->name('login');

//User Auhentication
Route::post('user-register',[AuthController::class,'register']);
Route::post('user-login',[AuthController::class,'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user-profile', function (Request $request) {
        return $request->user();
    });
    Route::get('user-logout',[AuthController::class,'logout']);
});

//Customer Authentication
Route::post('customer-register',[CustomerAuthController::class,'register']);
Route::post('/customer-login', [CustomerAuthController::class, 'login']);
Route::middleware(['auth:customer'])->group(function () {
    Route::get('/customer-profile', function (Request $request) {
        return $request->user();
    });
    Route::get('customer-logout',[CustomerAuthController::class,'logout'])->middleware('auth:sanctum');
});
