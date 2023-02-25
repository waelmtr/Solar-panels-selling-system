<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryDevice;

class CategoryDeviceController extends Controller
{
   public function get_categorydevice_by_id($category_id){
    $category_device = CategoryDevice::find($category_id);
    if(!$category_device)
    return response()->json(["message" => "category not found"] , 404);
    return response()->json(["category_data" => $category_device], 200);
   }

   public function get_all_categorydevices(Request $request){
    $name = $request->query('name');
    $category_devices = CategoryDevice::select('id' , 'name')->get();
    if(!$name)
    return response()->json(["categories" => $category_devices]);
    foreach($category_devices as $category_device){
        $category_device = $category_device->select('id' , 'name')->where('name' ,'like' ,'%'.$name.'%')->get();
        if($category_device->isEmpty())
        return response()->json(["message" => "category not found"] , 404);
        return response()->json(["categories" => $category_device]);
    }     
   }
}
