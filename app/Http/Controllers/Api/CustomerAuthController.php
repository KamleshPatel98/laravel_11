<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerAuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'string:50|required',
            'email'=>'required|max:50|unique:customers',
            'password'=>'required|confirmed|max:12|min:8|string',
        ]);
        if($validator->fails()){
            $errorMessage = $validator->errors()->first();
            return response()->json(['status'=>false,'message'=>'Validation error!','errors'=>$errorMessage],200);
        }

        $customer = Customer::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        $token = $customer->createToken($request->name);
        return response()->json(['status'=>true,'message'=>'Cusomer added successfully!','token'=>$token->plainTextToken,'name'=>$request->name],200);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|max:50|exists:customers',
            'password'=>'required|max:12|min:8|string',
        ]);
        if($validator->fails()){
            $errorMessage = $validator->errors()->first();
            return response()->json(['status'=>false,'message'=>'Validation error!','errors'=>$errorMessage],200);
        }

        $customer = Customer::where('email',$request->email)->first();
        if(!$customer || !Hash::check($request->password, $customer->password)){
            return response()->json(['status'=>false,'message'=>'Unauthorize','errors'=>'Your credentials are not match!'],500);
        }

        $token = $customer->createToken($customer->name);
        return response()->json(['status'=>true,'message'=>'Cusomer login successfully!','token'=>$token->plainTextToken,'name'=>$customer->name],200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['status'=>true,'message'=>'logout successfully!'],200);
    }
}
