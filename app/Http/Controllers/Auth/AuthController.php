<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Resources\UserResource;

// use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response([
                'message' => 'User not found',
                'success' => false,
            ]);
        }

        if (Hash::check($request->password, $user->password)) {
            $access_token = $user->createToken('authToken')->plainTextToken;
            return response([
                'message' => 'Login successful',
                'success' => true,
                'user' => $user,
                'access_token' => $access_token
            ]);
        }
        return response([
            'message' => 'Login failed',
            'success' => false,
        ]);
    }

    public function getPost(string $id){
        $post = User::find($id);
        return UserResource::collection($post);
        // return $post;
    }
}
