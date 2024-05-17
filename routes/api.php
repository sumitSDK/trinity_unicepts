<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login',[AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout'])->name('logout');

//Category 
Route::middleware('auth:sanctum')->post('save/category',[CategoryController::class, 'store'])->name('save.category');

//Tag
Route::middleware('auth:sanctum')->post('save/tag',[TagController::class, 'store'])->name('save.tag');

//Post
Route::middleware('auth:sanctum')->post('save/post',[PostController::class, 'store'])->name('save.post');
Route::middleware('auth:sanctum')->get('view/post/{id}',[PostController::class, 'view'])->name('view.post');
Route::middleware('auth:sanctum')->delete('delete/post/{id}',[PostController::class, 'delete'])->name('delete.post');
Route::middleware('auth:sanctum')->post('publish/post/{id}',[PostController::class, 'publish'])->name('publish.post');
Route::middleware('auth:sanctum')->post('update/post/{id}',[PostController::class, 'update'])->name('update.post');
Route::get('publish-post/list',[PostController::class, 'index'])->name('publish-post.list');