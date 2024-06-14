<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PharIo\Manifest\Email;
use App\Models\User;
use App\Models\resetPassword;


class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request){
        $status = User::where("email", $request->email)->first();
        if($status){
            $resetStatus = resetPassword::where("email", $request->email)->first();
            if(!$resetStatus){
                $forgotPwd = resetPassword::create([
                    'email' => $request->email,
                    'passcode' => random_int(100000, 999999)
                ]);
    
                return response()->json([
                    "message"=>"code aleady send",
                    "success"=>true,
                    "forgotPwd"=>$forgotPwd
                ]);
            }

            $resetStatus->passcode = random_int(100000, 999999);
            $resetStatus->save();
            return response()->json([
                "message"=>"code aleady send",
                "success"=>true,
                "forgotPwd"=>$resetStatus
            ]);
            
        }

        return response()->json([
            "message"=>"email not found",
            "success"=>false
        ]);
    }
}
