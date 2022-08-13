<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $name=request()->input('name');  
        $category_id= request()->input('category_id');
        $date=request()->input('date');

        $ProductQuery = Product::query();
    if ($name){
            $ProductQuery->where('name','like','%'.$name.'%');
     }

    if ($category_id){
            $ProductQuery->where('category_id' ,$category_id);
     }

    if ($date){
        $ProductQuery->where('date' ,$date);
    }

    $ProductQuery=$ProductQuery->get();

    foreach($ProductQuery as $product){
    $discounts = $product->discounts()->get();
    $maxDiscount = null;
    foreach ($discounts as $discount){
      if (Carbon::parse($discount['date']) <= now()){
         $maxDiscount = $discount;
        }
        if($maxDiscount==null){
            $product['current_price']= $product ->price;


        }
      if (!is_null($maxDiscount)){
        $discount_value = ($product->price*$maxDiscount['discount_percentage'])/100;
        $current_price= $product->price - $discount_value;
        $product['current_price']= $current_price;
        }
    }

    }
    foreach($ProductQuery as $product){
        $likes=$product->likes()->get();
        $likes=count($likes);
        $product['likes']=$likes;


    }
           return $ProductQuery;
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $name= $request->input('name');
       $price= $request->input('price');
       $description= $request->input('description');
       $date=$request->input('date');
       $img_url= $request->input('img_url');
       $quantity= $request->input('quantity');
       $category_id= $request->input('category_id');

       $auth=Auth::id();

       if(!$name || !$price || !$description ||!$date || !$quantity || !$category_id  )
            return response()->json(['message'=>'Invalid payload, all fields are required',
            'data' =>null],400);
       $image=$request->file('image');//تخزين الصورة
        $imagename=time().'.'.$image->getClientOriginalExtension(); //اسم ونوع الصورة
        $image->storeAs('/public',$imagename); //تخزين بالباك
        $img='/storage/'.$imagename;
        $product =Product::query()->create([
            'name'=> $name,
            'price'=> $price,
            'phone'=>Auth::user()->phone,
            'description'=>$description,
            'exp_date'=> $date,
            'quantity'=>$quantity,
            'img_url'=>$img,
            'category_id'=>$category_id,
            'user_id'=>$auth
        ]);
        foreach ($request->list_discounts as $discount){
            $product->discounts()->create([
                'date' => $discount['date'],
                'discount_percentage' => $discount['discount_percentage'],
           ]);
         }
        return response()->json(["Product Added Succesfully!"]);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $product= Product::find($id);
        $view = Product::where('id',$id)->value('view');
        Product::query()->find($id)->update([
            'view'=>$view+1
        ]);
        $discounts = $product->discounts()->get();
        $maxDiscount = null;
    foreach ($discounts as $discount){
         if (Carbon::parse($discount['date']) <= now()){
         $maxDiscount = $discount;
    }
    if (!is_null($maxDiscount)){
        $discount_value = ($product->price*$maxDiscount['discount_percentage'])/100;
        $product['current_price']= $product->price - $discount_value;
         }
         return $product;
 }

        return $product;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $auth=Auth::id();
        $user_id = Product::where('id',$id)->value('user_id');

        if( $user_id==$auth){
        Product::query()->find($id)->update([
            'name'=>$request-> name,
            'price'=> $request->price,
            'description'=>$request->description,
            'quantity'=>$request->quantity,
        ]);
        return response()->json(["Product Updated Succesfully!"],200);

    }
      else
         return  response()->json(["You cannot update this product"],400);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $auth=Auth::id();
        $user_id = Product::where('id',$id)->value('user_id');

        if( $user_id==$auth){
          product::query()->find($id)->delete();
          return response()->json(["Product Deleted Succesfully!"],200);}
        else
            return  response()->json(["You cannot delete this product"],400);

    }
}
