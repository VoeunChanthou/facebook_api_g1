<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use Illuminate\Http\Request;
use App\Models\Post;

class LikesController extends Controller
{
    

    /**
     * @OA\Post(
     *     path="/api/like",
     *     tags={"Like"},
     *     summary="add comment to post",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         description="Enter your id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */

    public function create(Request $request)
    {
        $post_id =  Post::find($request->post_id);
        if($post_id){
            $likesStatus = Likes::where('user_id',$request->user()->id)->first();
            if(!$likesStatus){
                $likes = new Likes();
                $likes->post_id = $request->post_id;
                $likes->user_id = $request->user()->id;
                $likes->save();
                return response()->json([
                    'message' => 'Liked successfully',
                   'success' => true
                ]);
            }
        }

        return response()->json([
           'message' => 'Post not found',
           'success' => false,
        ]);
    }

 

    

       /**
 * @OA\Delete(
 *     path="/api/unlike/{id}",
     *     tags={"Like"},
 *     summary="Unlike a post",
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
 
    public function destroy(Request $request, string $id)
    {
        $like =  Post::find($id);
        $likeStatus = Likes::where('user_id', $request->user()->id)->first();
        if($like){
            if($likeStatus){
                $like->delete();
                $like->save();
                return response()->json([
                    'message' => 'Unlike successfully',
                   'success' => true
                ]);
            }
        }

        return response()->json([
           'message' => 'Post not found',
           'success' => false,
        ]);
    }
}
