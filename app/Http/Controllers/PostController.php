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
        if($post == ''){
            return response()->json([
                "message"=>"The id not found",
                "success" => false,
            ]);
        }else{
            $post->update($request->validated());
            return response()->json([
                "message"=>"Updated successfully",
                "success"=>true,
                "post"=>$post,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $postId = Post::find($id);
        if($postId == ''){
            return response()->json([
                "message" => "The id isn't found.",
                "success" => false,
            ]);
        }else{
            $postId->delete();
            return response()->json([
                "message" => "Deleted successfully",
                "success" => true,
                "post" => $postId,
            ]);
        }
    }
}
