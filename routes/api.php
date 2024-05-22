<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthenticateApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Protected routes
Route::group(['middleware' => ['auth:sanctum']],function () {
    //student
    Route::get('student', [StudentController::class, 'index']);
    Route::post('student', [StudentController::class, 'upload']);
    Route::put('student/edit/{id}', [StudentController::class, 'edit']);
    Route::delete('student/delete/{id}', [StudentController::class, 'delete']);

    // User
    Route::post('/user',[AuthController::class,'update']);
    Route::post('auth/logout',[AuthController::class,'logout']);

    // Post
    Route::get('post', [PostController::class, 'index']); // all posts
    Route::post('post', [PostController::class, 'store']);// create post
    Route::get('post/{id}', [PostController::class, 'show']);// get single post
    Route::put('post/{id}', [PostController::class, 'update']);
    Route::delete('post/{id}', [PostController::class, 'destroy']);

      // Comment
      Route::get('post/{id}/comments', [CommentController::class, 'index']); // all comments
      Route::post('post/{id}/comments', [CommentController::class, 'store']);// create comment
      Route::put('comment/{id}', [CommentController::class, 'update']);
      Route::delete('comment/{id}', [CommentController::class, 'destroy']);

     // Like
     Route::post('post/{id}/likes', [LikeController::class, 'likeOrUnlike']);

    // return $request->user();
});

// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get('student', [StudentController::class, 'index']);
//     Route::post('student', [StudentController::class, 'upload']);
//     Route::put('student/edit/{id}', [StudentController::class, 'edit']);
//     Route::delete('student/delete/{id}', [StudentController::class, 'delete']);
// });


//public routes

Route::get('student/search/{name}',[StudentController::class,'search']);
Route::post('/auth/register',[AuthController::class,'register']);
Route::post('/auth/login',[AuthController::class,'loginUser']);


