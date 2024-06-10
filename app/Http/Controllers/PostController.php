<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(PostRequest $request)
    {
        $post = Post::create($request->validated());
        if($post){
        return response()->json([
            "message" => "Created successfully",
            "success" => true,
            "post" => $post
        ]);
        }else{
            return response()->json([
                "message" => "Failed to create",
                "success" => false,
            ]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request)
    {
        $post = Post::find($request->id);
        $post->update($request->validated());
        $id = Post::where($request->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
