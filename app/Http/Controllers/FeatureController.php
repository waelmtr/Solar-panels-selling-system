<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FeatureRequest;
use App\Models\Category;

class FeatureController extends Controller
{

   public function get_all_features_by_catigory_id(Request $request){
      $name = $request->query('name');
      $category_id = $request->query('category_id');
      $features = Category::find($category_id)->features;
         if(!$name){
           return response()->json(["features" => $features]);
         }
         else {
            $search_arr = [] ;
            foreach($features as $feature){
               if($feature['name'] == $name){
                  array_push($search_arr , $feature);
               }
            }
            if(count($search_arr)==0)
            return response()->json(["message" => "this feature not found"] , 404);
            return response()->json(["features" => $search_arr]);
         }
   }

   public function get_feature_by_id(Request $request , $feature_id){
      $category_id = $request->query('category_id');
      $features = Category::find($category_id)->features;
      foreach($features as $feature){
         if($feature['id'] == $feature_id)
         return response()->json(["feature" => $feature]);
      }
      return response()->json(["message" => "this feature not found"] , 404);
   }
}
