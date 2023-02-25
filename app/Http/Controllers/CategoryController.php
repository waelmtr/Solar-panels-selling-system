<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;


class CategoryController extends Controller
{
    public function get_all_categories(Request $request){
        $name = $request->query('name');
        $categories = Category::select('id' , 'name')->get();
        if(!$name)
        return response()->json(["categories" => $categories]);
        foreach($categories as $category){
            $category = $category->select('id' , 'name')->where('name' ,'like' ,'%'.$name.'%')->get();
            if($category->isEmpty())
            return response()->json(["message" => "this category not found"] , 404);
            return response()->json(["categories" => $category]);
        }        
    }

    public function get_category_by_id(Request $request , $category_id ){
        $category = Category::find($category_id);
        if(!$category)
        return response()->json(["message" => "this category not found"], 404);
        return response()->json(["category_data" => $category]); 
    }
}
