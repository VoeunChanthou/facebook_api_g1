<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request){

       $request->validate([
        'email' =>'required|email',
        'password' =>'required|string|min:6'
       ]); 

       $user = User::where('email', $request->email)->first();
       if($user){
        return response([
            'message' =>'User not found',
            'success' => false,
        ]);
       }
       
       if(Hash::check($request->password, $user->password)){
        $access_token = $user->createToken('authToken')->plainTextToken;
        return response([
            'message' =>'Login successful',
            'success' => true,
            'user'=>$user,
            'access_token'=>$access_token
        ]);
       }
       return response([
        'message' =>'Login failed',
        'success' => false,
       ]);
    }

    public function logout(Request $request){
        if ($request->user()->tokens()->delete()) {
            return response()->json(['message' => 'Successfully logout'],200);
        }else {
            return response()->json(['message' => 'Unable to logout'],500);
        }
    }
}
