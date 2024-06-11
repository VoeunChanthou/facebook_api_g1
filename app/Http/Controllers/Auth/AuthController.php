<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $request->validate([
            'name' =>'required',
            'email' =>'required',
            'password' =>'required',
            
        ]);

        $user_exist = User::where('email', $request->email)->first();
        if($user_exist){
            return response([
                'message' => 'Email Already Exist !',
                'success' => false,
            ]);   
        }

        // $password = Hash::make($request->password);
        // $request->password =$password;
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response([
            'message' => 'User Created Successfully !',
            'user' => $user,
           'success' => true,
        ]);
    }
}
