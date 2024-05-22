<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index($id)
    {

        $post = Post::find($id);

        if(!$post){
            return response([
                'message'=> 'Post Not Found'
            ], 403);
        }
        return response([
            'comments'=> $post->comments()-> with('user:id,name,image')->get()

        ], 200);
    }

    public function store(Request $request, $id)
    {

        $post = Post::find($id);

        if(!$post){
            return response([
                'message'=> 'Post Not Found'
            ], 403);
        }

         //validate fields
         $validate = $request->validate([
            'comment'=>'required|string'
        ]);

        Comment::create([
            'comment'=> $validate['comment'],
            'post_id' => $id,
            'user_id'=> auth()->user()->id
        ]);

        return response([
            'message'=> 'comment created'
        ], 200);

    }

    // update comment
    public function update(Request $req, $id)
    {
        $comment = Comment::find($id);
        if(!$comment){
            return response([
                'message'=>'Comment not found'
            ],403);
        }

        if($comment->user_id != auth()->user()->id){
            return response([
                'message' => 'Permission denied',
            ],403);
        }
          //validate fields
          $validate = $req->validate([
            'comment'=>'required|string'
        ]);

        $comment -> update([
            'comment' => $validate['comment']
        ]);

        return response([
            'message'=> 'comment updated'
        ], 200);

    }

    //delete a comment
    public function destroy($id){
        $comment = Comment::find($id);
        if(!$comment){
            return response([
                'message'=>'Comment not found'
            ],403);
        }

        if($comment->user_id != auth()->user()->id){
            return response([
                'message' => 'Permission denied',
            ],403);
        }

        $comment->delete();

        return response([
            'message'=> 'comment deleted'
        ], 200);
    }


}
