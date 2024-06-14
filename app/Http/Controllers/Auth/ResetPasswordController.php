<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\resetPassword;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request){
        $status = resetPassword::where('email', $request->email)->first();
        if($status){
            if($status->passcode == $request->passcode){
                $user = User::where('email', $request->email)->first();
                if($user){
                    $user->password = Hash::make($request->password);
                    $user->save();
                    $status->delete();
                    return response()->json([
                        "message"=>"change password successfully",
                        "success"=>true,
                        "user"=>$user
                    ]);
                }
            };
        }
        return response()->json([
            "message"=>"change password failed",
            "success"=>false
        ]);
    }
}
