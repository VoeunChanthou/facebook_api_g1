<?php

namespace App\Http\Controllers;
use App\Models\Comments;
use Egulias\EmailValidator\Parser\Comment;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
class CommentsController extends Controller
{



     /**
 * @OA\Get(
 *     path="/api/comments",
     *     tags={"Comments"},
 * 
 *     summary="Get all comments",
 *     security={{"bearerAuth":{}}},
 * @OA\Response(
 *      response=200,
 *      description="The resource was successfully retrieved"
 * )
 * )
 */
    public function index() {
        return CommentResource::collection(Comments::all());
    }
    

    /**
     * @OA\Post(
     *     path="/api/add-comment",
     *     tags={"Comments"},
     * 
     *     summary="add comment to post",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="query",
     *         description="Enter your id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="body",
     *         in="query",
     *         description="Enter your comment",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */

    public function createComment(Request $request) {
        $request->validate([
            'post_id' => 'required',
            'body' => 'required'
        ]);
    
        if (Auth::check()) {
            $comment = Comments::create([
                'post_id' => $request->post_id,
                'body' => $request->body,
                'user_id' => Auth::id(),
            ]);
            return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 200);
        } else {
            return response()->json(['message' => 'Comment creation failed, unauthorized'], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/update/comment",
     *     tags={"Comments"},
     * 
     *     summary="update comments",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="Enter your id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="body",
     *         in="query",
     *         description="Enter your comment",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */
    

    public function update (Request $request){
        $comments = Comments::all();
        for($i=0;$i<count($comments);$i++){
            if($comments[$i]->id == $request->id){
                $commentUpdate = $comments[$i]->update([
                    'body' => $request->body,
                ]);
                return response()->json([
                    "message"=>"Updated successfully",
                    "success" => true,
                    "comment"=>$comments[$i]
                ]);
            }
        }
        return response()->json([
            "message"=>"The id not found",
            "success" => false,
        ]);
         
     
    }


      /**
 * @OA\Delete(
 *     path="/api/delete/comment/{id}",
     *     tags={"Comments"},
 * 
 *     summary="Delete comment",
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

    public function destroy(string $id){
        $commentId = Comments::find($id);
        if($commentId == ''){
            return response()->json([
                "message"=>"The id not found",
                "success" => false,
            ]);
        }else{
            $commentId->delete();
            return response()->json([
                "message"=>"Deleted successfully",
                "success" => true,
                "comment" => $commentId,
            ]);
        }
    }
}

