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
    public function index() {
        return CommentResource::collection(Comments::all());
    }
    
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

