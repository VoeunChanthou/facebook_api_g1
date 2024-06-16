<?php

namespace App\Http\Controllers;

use App\Models\profile;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ProfileResource;

class ProfileController extends Controller
{


    /**
     * @OA\Post(
     *     path="/api/upload/profile",
     *     tags={"Profile"},
     *     summary="Login to the account",
     *     security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="img",
     *                     type="file",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */


    public function create(ProfileRequest $request)
    {
        $imgagName = Str::random(32) . "." . $request->img->getClientOriginalExtension();
        $user = profile::where('user_id', $request->user()->id)->first();
        if (!$user) {

            $pl = profile::create([
                'img' => $imgagName,
                'user_id' => $request->user()->id,
            ]);
            Storage::disk('public')->put($imgagName, file_get_contents($request->img));

            return response()->json([
                'message' => 'Profile created successfully',
                'success' => true,
                'profile' => $pl,
            ]);
        }

        return response()->json([
            'message' => 'Profile created already exists',
            'success' => false,
        ]);

    }



    /**
     * @OA\Post(
     *     path="/api/change/profile",
     *     tags={"Profile"},
     *     summary="Update profile",
     *     security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="img",
     *                     type="file",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */


    public function edit(ProfileRequest $request)
    {
        $imgagName = Str::random(32) . "." . $request->img->getClientOriginalExtension();
        $user = profile::where('user_id', $request->user()->id)->first();
        if ($user) {
            Storage::disk('public')->put($imgagName, file_get_contents($request->img));
            $user->img = $imgagName;
            $user->save();
            return response()->json([
                'message' => 'Profile updated successfully',
                'success' => true,
                'profile' => $user,
            ]);
        }

        return response()->json([
            'message' => 'Profile not found',
            'success' => false,
        ]);
    }
}
