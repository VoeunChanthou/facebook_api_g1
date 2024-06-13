<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\FriendResource;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $list = [];
        $friends = FriendResource::collection(Friend::all());
        // foreach ($friends as $friend) {
        //     if ($friend->user_id == $request->user()->id) {
        //         array_push($list, $friend);
        //     };
        // };
        // if(count($list) > 0) {
        //     return response()->json([
        //         "message" => "Success",
        //         "success" => true,
        //         "data" => $list,
        //     ]);
        // };

        // return response()->json([
        //     "message" => "You don't have friends yet",
        //     "success"=>false
        // ]);
        return $friends;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addFriend(Request $request)
    {
        $yourId = $request->user()->id;
        $friends = User::where('id', $request->friend_id)->first();
        if($friends){
            $currentFriend = Friend::where('friend_id', $friends->friend_id)->first();
            if(!$currentFriend){
                if($friends->id != $yourId){
                    $friend = Friend::create([
                        'user_id'=> $yourId,
                        'friend_id'=> $friends->id
                    ]);
    
                    return response()->json([
                        "message"=>"your add your friend successfully",
                        "success"=> true,
                        "friend"=>$friend 
                    ]);
                }
            }else{
                return response()->json([
                    "message"=>"your friend already exists",
                    "success"=>false
                ]);
            }
            
        }

        return response()->json([
            "message"=>"you cannot add a friend",
            "success"=>false,
        ]);


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Friend $friend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Friend $friend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Friend $friend)
    {
        //
    }
}
