<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', [UserController::class,'store']);
Route::post('/login', [UserController::class,'login']);

Route::get('/posts', [PostController::class,'index']);
Route::post('/posts', [PostController::class,'store']);
Route::get('/post/{id}', [PostController::class,'show']);
Route::get('/search/{word}', [PostController::class,'search']);
Route::get('/posts/blogger/{id}', [PostController::class,'myPosts']);

Route::get('/bloggers', [UserController::class,'bloggers']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
