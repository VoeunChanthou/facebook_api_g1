<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Str;

class ForgotPassword extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $status = User::where('email', $request->email)->first();
        if($status){
            return random_int(100000, 999999);;
        }
    }
}
