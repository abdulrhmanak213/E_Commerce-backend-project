<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/posts/{post}', function($post){

$posts=[
  'my-first-post'=>'hi',
  'my-second-post'=>'hello'
];
if(!array_key_exists($post,$posts)){
    abort(404,'sorry');

}
 return view('post',[
  'post'=>$posts[$post]
 ]);

*/
/*Route::get('',function(){
  return "hello world";
});*/
//Route::get('/post/{id}','PostController@index');

Route::get('/post{name}', 'PostController@create');
Route::get('/checkuser', 'PostController@checkusername');
Route::get('/userlist/{id}','PostController@show');
