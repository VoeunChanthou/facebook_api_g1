<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
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

    public function loggout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            return response()->json(['message' => 'Successfully logged out'], 200);
        } else {
            return response()->json(['message' => 'User not authenticated'], 500);
        }
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent'], 200);
        } else {
            return response()->json(['message' => 'Unable to send reset link'], 500);
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successful'], 200);
        } else {
            return response()->json(['message' => 'Password reset failed'], 500);
        }
    }
}
