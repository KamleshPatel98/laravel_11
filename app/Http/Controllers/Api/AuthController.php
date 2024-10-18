<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'string:255|required',
            'email'=>'required|max:255|unique:users',
            'password'=>'required|confirmed|max:12|min:8|string',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        $token = $user->createToken($request->name);

        return response()->json(['status'=>true,'message'=>'User added successfully!','token'=>$token->plainTextToken],200);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|max:255|exists:users',
            'password'=>'required|max:12|min:8|string',
        ]);
        
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['status'=>'false','message'=>'Your credentials are not match!'],500);
        }

        $token = $user->createToken($user->name);
        return response()->json(['status'=>true,'message'=>'Login successfully!','token'=>$token->plainTextToken,'name'=>$user->name],200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['status'=>true,'message'=>'logout successfully!'],200);
    }
}
