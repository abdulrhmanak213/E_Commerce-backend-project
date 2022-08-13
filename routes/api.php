<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Controller;
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
Route::get('/test-online', function () {
    dd('i am online ^_^');
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("auth")->group(function(){
    Route::post('/sign-up',[UserController::class,'creatAccount']);
    Route::post('/login',[UserController::class,'login']);
    Route::get('/myProduct',[UserController::class,'myProducts'])->middleware('auth:api');
});


Route::prefix('categories')->group(function(){
    Route::get('/',[CategoryController::class ,'index']);
    Route::post('/',[CategoryController::class ,'store']);
    Route::get('/{id}',[CategoryController::class ,'show']);
    Route::put('/{id}',[CategoryController::class ,'update']);
    Route::delete('/{id}',[CategoryController::class ,'destroy']);


});

Route::middleware(['auth:api'])->group(function(){
Route::prefix('products')->group(function(){
    Route::get('/',[ProductController::class ,'index']);
    Route::post('/',[ProductController::class ,'store']);
    Route::get('/{id}',[ProductController::class ,'show']);
    Route::put('/{id}',[ProductController::class ,'update']);
    Route::delete('/{id}',[ProductController::class ,'destroy']);
    Route::prefix('/{product}/comments')->group(function (){
        Route::get('/', [CommentController::class, 'index']);
        Route::post('/', [CommentController::class, 'store']);
        Route::put('/{comment}', [CommentController::class, 'update']);
        Route::delete('/{id}', [CommentController::class, 'destroy']);
      });
    Route::prefix('/{product}/likes')->group(function (){
        Route::get('/', [LikeController::class, 'index']);
        Route::post('/', [LikeController::class, 'store']);
        Route::get('/islike', [LikeController::class, 'show']);


     });
    });
});

