<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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



Route::post('/register',[AuthController::class, 'register'] );
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot',[ForgotPasswordController::class,'forgotPassword']);
Route::post('password/reset',[ResetPasswordController::class,'resetPassword']);


Route::middleware('auth:sanctum')->group(function () {
    
    //Route post//
    Route::get('/posts', [PostController::class,'index']);
    Route::post('/add-post',[PostController::class, 'create']);
    Route::put('/update/post',[PostController::class, 'update']);
    Route::delete('/delete/post/{id}',[PostController::class, 'destroy']);
    
    Route::get('/get-post/{id}', [PostController::class,'getPost']);
    Route::get('/get-post-user', [AuthController::class,'getPost']);
    Route::get('/show/post/{id}', [AuthController::class,'ShowOnePost']);
    Route::post('loggout', [AuthController::class, 'loggout']);


    //Friends
    Route::get('/friends', [FriendController::class, 'index']);
    Route::post('/add-friend', [FriendController::class, 'addFriend']);
    Route::get('/friends-request',[FriendController::class, 'requestFriend']);
    Route::put('/confirm-friend/{id}',  [FriendController::class, 'confrimFriend']);
    Route::delete('/delete-friend/{id}', [FriendController::class, 'deleteFriend']);


    //Profile
    Route::post('/upload/profile', [ProfileController::class, 'create']);
    Route::put('/updatPl', [AuthController::class, 'update_pl']);
    Route::post('/change/profile', [ProfileController::class, 'edit']);
    Route::get('/view/profile', [AuthController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function (){
    // Route Comment//
    Route::post('/add-comment',[CommentsController::class, 'createComment']);
    Route::get('/comments', [CommentsController::class, 'index']);
    Route::put('/update/comment',[CommentsController::class, 'update']);
    Route::delete('/delete/comment/{id}',[CommentsController::class, 'destroy']);

});
Route::middleware('auth:sanctum')->group(function (){
    // Route Comment//
    Route::post('/like', [LikesController::class, 'create']);
    Route::delete('/unlike/{id}', [LikesController::class, 'destroy']);

});