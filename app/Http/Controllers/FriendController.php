<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\FriendResource;

class FriendController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/friends",
     *     tags={"Friend"},
     *     
     *     summary="Delete post by id",
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */

    public function index(Request $request)
    {
        $list = [];
        $friends = FriendResource::collection(Friend::all());
        foreach ($friends as $friend) {
            if ($friend->user_id == $request->user()->id) {
                if ($friend->confirmed != 0) {
                    array_push($list, $friend);
                }
            }
            ;
        }
        ;
        if (count($list) > 0) {
            return response()->json([
                "message" => "Success",
                "success" => true,
                "data" => $list,
            ]);
        }
        ;

        return response()->json([
            "message" => "You don't have friends yet",
            "success" => false
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/add-friend",
     *     tags={"Friend"},
     * 
     *     summary="Login to the account",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="friend_id",
     *         in="query",
     *         description="Enter your friend_id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */

    public function addFriend(Request $request)
    {
        $yourId = $request->user()->id;
        $friends = User::where('id', $request->friend_id)->first();
        if ($friends) {
            $currentFriend = Friend::where('friend_id', $friends->friend_id)->first();
            if (!$currentFriend) {
                if ($friends->id != $yourId) {
                    $friend = Friend::create([
                        'user_id' => $yourId,
                        'friend_id' => $friends->id,
                    ]);

                    return response()->json([
                        "message" => "your add your friend successfully",
                        "success" => true,
                        "friend" => $friend
                    ]);
                }
            } else {
                return response()->json([
                    "message" => "your friend already exists",
                    "success" => false
                ]);
            }

        }

        return response()->json([
            "message" => "you cannot add a friend",
            "success" => false,
        ]);


    }


    /**
     * @OA\Get(
     *     path="/api/friends-request",
     *     tags={"Friend"},
     * 
     *     summary="get list of friends requests",
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */

    public function requestFriend(Request $request)
    {
        $list = [];
        $friends = FriendResource::collection(Friend::all());
        foreach ($friends as $friend) {
            if ($friend->user_id == $request->user()->id) {
                if ($friend->confirmed == 0) {
                    array_push($list, $friend);
                }
            }
            ;
        }
        ;
        if (count($list) > 0) {
            return response()->json([
                "message" => "Success",
                "success" => true,
                "data" => $list,
            ]);
        }
        ;

        return response()->json([
            "message" => "You don't have friends request yet",
            "success" => false
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/confirm-friend/{id}",
     *     tags={"Friend"},
     * 
     *     summary="Confirm your friend",
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


    public function confrimFriend(Request $request, string $id)
    {

        $friend = Friend::find($id);
        if ($friend && $friend->user_id != $request->user()->id && $friend->confirmed == 0) {
            $friend->confirmed = 1;
            $friend->save();
            return response()->json([
                "message" => "Success",
                "success" => true,
                "data" => $friend,
            ]);
        }
        return response()->json([
            "message" => "You don't have friends request yet",
            "success" => false
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/delete-friend/{id}",
     *     tags={"Friend"},
     * 
     *     summary="Delete friend",
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


    public function deleteFriend(string $id)
    {
        $friend = Friend::find($id);
        if ($friend) {
            $friend->delete();
            return response()->json([
                "message" => "delete friend successfully",
                "success" => true,
                "friend" => $friend
            ]);
        }

        return response()->json([
            "message" => "delete friend failed",
            "success" => false
        ]);
    }
}
