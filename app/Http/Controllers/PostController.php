<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     * 
     *     summary="Get all post",
     *     security={{"bearerAuth":{}}},
     * @OA\Response(
     *      response=200,
     *      description="The resource was successfully retrieved"
     * )
     * )
     */
    public function index()
    {
        return PostResource::collection(Post::all());
    }


    /**
     * @OA\Post(
     *     path="/api/add-post",
     *     tags={"Posts"},
     *     summary="Login to the account",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Enter your title",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Enter your description",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="ImgUrl",
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

    public function create(Request $request)
    {
        $imgagName = Str::random(32) . "." . $request->ImgUrl->getClientOriginalExtension();
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'ImgUrl' => $imgagName,
            'user_id' => $request->user()->id
        ]);

        Storage::disk('public')->put($imgagName, file_get_contents($request->ImgUrl));
        if ($post) {
            return response()->json([
                "message" => "Created successfully",
                "success" => true,
                "post" => $post
            ]);
        } else {
            return response()->json([
                "message" => "Failed to create",
                "success" => false,
            ]);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/update/post",
     *     tags={"Posts"},
     *     summary="Login to the account",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Enter your id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Enter your title",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="description",
     *         in="query",
     *         description="Enter your description",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    public function update(Request $request)
    {
        $post = Post::find($request->id);
        if ($post != '' && $request->user()->id == $post->user_id) {

            $post->update([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json([
                "message" => "Updated successfully",
                "success" => true,
                "post" => $post,
            ]);

        } else {
            return response()->json([
                "message" => "The id not found",
                "success" => false,
            ]);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/delete/post/{id}",
     *     tags={"Posts"},
     *     
     *     summary="Delete post by id",
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

    public function destroy(string $id)
    {
        $postId = Post::find($id);
        if ($postId == '') {
            return response()->json([
                "message" => "The id isn't found.",
                "success" => false,
            ]);
        } else {
            $postId->delete();
            return response()->json([
                "message" => "Deleted successfully",
                "success" => true,
                "post" => $postId,
            ]);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/get-post/{id}",
     *     tags={"Posts"},
     *     summary="Delete post by id",
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

    public function getPost(string $id)
    {
        $post = PostResource::collection(Post::all());
        $posts = [];
        for ($i = 0; $i < count($post); $i++) {
            if ($post[$i]->id == $id) {
                return response()->json([
                    "message" => "Successfully",
                    "success" => true,
                    "posts" => $post[$i],
                ]);
            }
        }
        return response()->json([
            "message" => "post not found",
            "success" => false,
        ]);
    }
}
