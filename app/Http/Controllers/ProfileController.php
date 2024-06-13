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
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     return $request->user();
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ProfileRequest $request)
    {
        $imgagName = Str::random(32).".".$request->img->getClientOriginalExtension();
        $user = profile::where('user_id', $request->user()->id)->first();
        if(!$user){

            $pl = profile::create([
                'img'=>$imgagName,
                'user_id'=>$request->user()->id,
            ]);
            Storage::disk('public')->put($imgagName, file_get_contents($request->img));
    
            return response()->json([
                'message'=>'Profile created successfully',
                'success'=>true,
                'profile'=>$pl,
            ]);
        }

        return response()->json([
            'message'=>'Profile created already exists',
            'success'=>false,
        ]);

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProfileRequest $request)
    {
        $imgagName = Str::random(32).".".$request->img->getClientOriginalExtension();
        $user = profile::where('user_id', $request->user()->id)->first();
        if($user){
            Storage::disk('public')->put($imgagName, file_get_contents($request->img));
            $user->img = $imgagName;
            $user->save();
            return response()->json([
                'message'=>'Profile updated successfully',
                'success'=>true,
                'profile'=>$user,
            ]);
        }

        return response()->json([
            'message'=>'Profile not found',
            'success'=>false,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(profile $profile)
    {
        //
    }
}
