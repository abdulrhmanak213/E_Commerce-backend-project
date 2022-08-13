<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\+Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->name==null)
          return response()->json(["invalid name"]);
        Category::query()->create([
        'name'=>$request->name,
    ]);
    return response()->json(["Ctegory Added Succesfully!"]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Category::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $myid=Category::query()->find($id);
        if($myid==null)
          return response()->json(["invalid id"]);
       Category::query()->find($id)->update([
           'name'=>$request->name
       ]);
       return response()->json(["Category Updated Succesfully!"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $myid=Category::query()->find($id);
        if($myid==null)
          return response()->json(["invalid id"]);

     Category::query()->find($id)->delete();
     return response()->json(["Category Deleted Succesfully!"]);
    }
}
