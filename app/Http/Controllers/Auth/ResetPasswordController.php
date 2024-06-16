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


    /**
     * @OA\Post(
     *     path="/api/password/reset",
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
     *     @OA\Parameter(
     *         name="passcode",
     *         in="query",
     *         description="Enter your passcode",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Enter your new password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */

    public function resetPassword(Request $request)
    {
        $status = resetPassword::where('email', $request->email)->first();
        if ($status) {
            if ($status->passcode == $request->passcode) {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    $status->delete();
                    return response()->json([
                        "message" => "change password successfully",
                        "success" => true,
                        "user" => $user
                    ]);
                }
            }
            ;
        }
        return response()->json([
            "message" => "change password failed",
            "success" => false
        ]);
    }
}
