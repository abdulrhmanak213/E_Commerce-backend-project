<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $product= Product::find($id);
        $comments = $product->comments()->get();
        return response()->json($comments);
       //return $product;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $request->validate([
            'value' => ['required', 'string','min:1', 'max:400']
        ]);
        $product= Product::find($id);
        $comment = $product->comments()->create([
            'user_id' => Auth::id(),
            'value' => $request->value
        ]);
        return response()->json($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id, $comment)
    {
        $request->validate([
            'value' => ['required', 'string','min:1', 'max:400']
        ]);
        $auth=Auth::id();
        $user_id = Product::where('id',$id)->value('user_id');
        if( $user_id==$auth){
            Comment::query()->find($comment)->update([
                'value' => $request->value]);
            return response()->json(["comment Updated Succesfully!"],200);
            }

        else
             return  response()->json(["You cannot update this comment"],400);
            return $comment;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$comment)
    {
        $auth=Auth::id();
        $user_id = Product::where('id',$id)->value('user_id');
        if( $user_id==$auth){
            Comment::query()->find($comment)->delete();
            return response()->json(["Product Deleted Succesfully!"],200);}
        else
            return  response()->json(["You cannot delete this product"],400);

    }
}
