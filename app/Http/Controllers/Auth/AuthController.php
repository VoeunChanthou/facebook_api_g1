<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;



class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user and generate JWT token",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function login(Request $request){

       $request->validate([
        'email' =>'required|email',
        'password' =>'required'
       ]); 

       $user = User::where('email', $request->email)->first();
       if(!$user){
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
}
