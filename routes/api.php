<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;

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

// Route::post('/add-post',[PostController::class, 'create']);
// Route::get('/posts', [PostController::class,'index']);


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/add-post',[PostController::class, 'create']);
    Route::get('/posts', [PostController::class,'index']);
    Route::put('/update/post',[PostController::class, 'update']);
    Route::delete('/delete/post/{id}',[PostController::class, 'destroy']);

    Route::get('/get-post-user/{id}', [AuthController::class,'getPost']);
});