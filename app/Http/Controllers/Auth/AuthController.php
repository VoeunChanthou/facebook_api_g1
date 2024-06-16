<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Models\profile;
use App\Http\Resources\ProfileResource;
use Symfony\Component\HttpKernel\Profiler\Profile as ProfilerProfile;

class AuthController extends Controller
{


    public function register(Request $request){
        $request->validate([
            'image' =>'required',
            'name' =>'required',
            'email' =>'required',
            'password' =>'required',
            
        ]);

        $user_exist = User::where('email', $request->email)->first();
        if($user_exist){
            return response([
                'message' => 'Email Already Exist !',
                'success' => false,
            ]);   
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response([
            'message' => 'User Created Successfully !',
            'user' => $user,
           'success' => true,
        ]);
    }

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

    public function getPost(Request $request){
        $posts = Post::all();
        $list = [];
        $userId = $request->user()->id;
        for($i=0; $i<count($posts); $i++){
            if($posts[$i]->user_id === $userId){
                array_push($list, $posts[$i]);
            }
        }
        if(count($list) > 0){
            return response()->json([
                "message"=>"get post of user successfully",
                "success" => true,
                "posts"=>$list
            ]);

        }else{
            return response()->json([
                "message"=>"user didn't post",
                "success"=>false,
            ]);
        }
    }

    public function showOnePost(Request $request, string $id){
        $posts = Post::all();
        $userId = $request->user()->id;
        for( $i= 0; $i<count($posts); $i++){
            if($posts[$i]->user_id == $userId && $posts[$i]->id  == $id){
                return response()->json([
                    "message"=>"Request post successfully",
                    "success"=>true,
                    "post" => $posts[$i],
                ]);
            }
        }
        return response()->json([
            "messsage"=>"Post is not found",
            "success"=> false,
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

    public function index(Request $request){
        $user = UserResource::collection(User::all());
        $userId = $request->user()->id;
        for($i=0; $i<count($user); $i++){
            if($user[$i]->id == $userId){
                return response()->json([
                    "message" => "view profile successfully",
                    "success" => true,
                    "user" => $user[$i],
                ]);
            }
        }
    }

    public function update_pl(Request $request){
        $user = $request->user();
        $user->update(
            [
                'name'=>$request->name,
            ]
        );
        return response()->json([
            "message" => "update profile successfully",
            "success" => true,
            "user" => $user,
        ]);
    }

}


