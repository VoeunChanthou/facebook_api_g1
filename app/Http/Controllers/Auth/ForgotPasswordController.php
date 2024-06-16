<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PharIo\Manifest\Email;
use App\Models\User;
use App\Models\resetPassword;


class ForgotPasswordController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/password/forgot",
     *     tags={"reset_password"},
     * 
     *     summary="Fogot a password",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Enter your email address",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function forgotPassword(Request $request){
        $status = User::where("email", $request->email)->first();
        if($status){
            $resetStatus = resetPassword::where("email", $request->email)->first();
            if(!$resetStatus){
                $forgotPassword = new resetPassword();
                $forgotPassword->email = $request->email;
                $forgotPassword->passcode = random_int(100000, 999999);

                $forgotPassword->save();
    
                return response()->json([
                    "message"=>"code aleady send",
                    "success"=>true,
                    "You passcode"=>$forgotPassword
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
