<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryDevice;

class DeviceController extends Controller
{
    public function get_device_by_id(Request $request , $device_id){
        $categorydevice_id = $request->query('categorydevice_id');
        $categorydevice = CategoryDevice::find($categorydevice_id);
        if(!$categorydevice)
        return response()->json(["message" => "category that contain this device not found"] , 404);
        $devices = $categorydevice->devices;
        foreach($devices as $device){
           if($device['id'] == $device_id)
           return response()->json(["device" => $device]);
        }
        return response()->json(["message" => "this device not found"] , 404);
    }

    public function get_all_devices_by_catigory_id(Request $request){
        $name = $request->query('name');
        $categorydevice_id = $request->query('categorydevice_id');
        $categorydevice = CategoryDevice::find($categorydevice_id);
        if(!$categorydevice)
        return response()->json(["message" => "category that contain this device not found"] , 404);
        $devices = $categorydevice->devices ;
        if(!$name){
            return response()->json(["devices" => $devices]);
        }
        else {
            $search_arr = [] ;
            foreach($devices as $device){
                if($device['name'] == $name){
                   array_push($search_arr , $device);
                }
            }
            if(count($search_arr)==0)
            return response()->json(["message" => "this device not found"] , 404);
            return response()->json(["devices" => $search_arr]);
        }
    }
}
