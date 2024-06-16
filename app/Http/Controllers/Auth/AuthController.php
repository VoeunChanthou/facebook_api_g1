<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{


    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"authentication"},
     *     summary="Register an account",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Enter your email address",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Enter your password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',

        ]);

        $user_exist = User::where('email', $request->email)->first();
        if ($user_exist) {
            return response([
                'message' => 'Email Already Exist !',
                'success' => false,
            ]);
        }

        // $password = Hash::make($request->password);
        // $request->password =$password;
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




    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"authentication"},
     *     summary="Login to the account",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="Enter your email address",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="Please enter your password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */

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

    /**
     * @OA\Get(
     *     path="/api/get-post-user",
     *     tags={"Posts"},
     *     summary="Get a post resource",
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */
    public function getPost(Request $request)
    {
        $posts = Post::all();
        $list = [];
        $userId = $request->user()->id;
        for ($i = 0; $i < count($posts); $i++) {
            if ($posts[$i]->user_id === $userId) {
                array_push($list, $posts[$i]);
            }
        }
        if (count($list) > 0) {
            return response()->json([
                "message" => "get post of user successfully",
                "success" => true,
                "posts" => $list
            ]);

        } else {
            return response()->json([
                "message" => "user didn't post",
                "success" => false,
            ]);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/show/post/{id}",
     *     tags={"Posts"},
     *     summary="Get a post resource",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */

    public function showOnePost(Request $request, string $id)
    {
        $posts = Post::all();
        $userId = $request->user()->id;
        for ($i = 0; $i < count($posts); $i++) {
            if ($posts[$i]->user_id == $userId && $posts[$i]->id == $id) {
                return response()->json([
                    "message" => "Request post successfully",
                    "success" => true,
                    "post" => $posts[$i],
                ]);
            }
        }
        return response()->json([
            "messsage" => "Post is not found",
            "success" => false,
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/loggout",
     *     tags={"authentication"},
     *     summary="Logout account in facebook",
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/view/profile",
     *     tags={"Profile"},
     *     summary="view profile",
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */

    public function index(Request $request)
    {
        $user = UserResource::collection(User::all());
        $userId = $request->user()->id;
        for ($i = 0; $i < count($user); $i++) {
            if ($user[$i]->id == $userId) {
                return response()->json([
                    "message" => "view profile successfully",
                    "success" => true,
                    "user" => $user[$i],
                ]);
            }
        }
    }


    /**
     * @OA\Put(
     *     path="/api/updatPl",
     *     tags={"Profile"},
     *     summary="Confirm your friend",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */

    public function update_pl(Request $request)
    {
        $user = $request->user();
        $user->update(
            [
                'name' => $request->name,
            ]
        );
        return response()->json([
            "message" => "update profile successfully",
            "success" => true,
            "user" => $user,
        ]);
    }

}


