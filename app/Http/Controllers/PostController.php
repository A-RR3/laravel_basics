<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    //get all posts
    public function index()
    {

        $posts = Post::orderBy('created_at', 'desc')
            ->with('user:id,name,image')
            ->withCount('comments', 'likes')
            ->paginate(2);

        return response([
            'current_page' => $posts->currentPage(),
            'last_page' => $posts->lastPage(),
            'item_count' => $posts->count(),
            'posts' => $posts->items(),

        ], 200);
    }

    // get single post
    public function show($id)
    {
        return response([
            'posts' => Post::where('id', '$id')->withCount('comments', 'likes')->get()

        ], 200);
    }

    public function store(Request $request)
    {
        // validate fields
        $validate = Validator::make($request->all(), [
            'body' => 'required|string',
            'image' => 'image|mimes:jpg,jpeg,png,svg'
        ], [
            'body.required' => 'body is required',
            'image.mimes' => 'extention is not allowed for image',
            'image.image' => 'only image is allowed'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validate->errors()->first()

            ], 201);
        }

        if ($request->hasFile('image')) {
            $fileName = $this->saveImage($request, 'uploads/images');
        }

        $post = Post::create([
            'body' => $request->body,
            'user_id' => auth()->user()->id,
            'image' => $fileName
        ]);
        //skip image


        return response([
            'message' => 'Post created',
            'post' => $post

        ], 201);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post Not Found'
            ], 403);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied',
            ], 403);
        }
        //validate fields
        $validate = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' => $validate['body']
        ]);

        return response([
            'message' => 'Post updated',
            'post' => $post
        ], 200);
    }

    //delete post
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post Not Found'
            ], 403);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied',
            ], 403);
        }

        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();

        return response([
            'message' => 'Post deleted',
            'post' => $post
        ], 200);
    }
}
