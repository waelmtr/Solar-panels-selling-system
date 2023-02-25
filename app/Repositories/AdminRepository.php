<?php

namespace App\Repositories;

use App\Interfaces\AdminInterface;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\DeviceRequest;
use App\Models\Category;
use App\Models\CategoryDevice;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use File;



class AdminRepository implements AdminInterface {

    public function createCategory($request){
        $category = new Category();
        $category->name = $request->name;
        $category->features = [] ;
        $category->products = [] ;
        $category->save();
        return response()->json(["category_data" => $category], 200);
    }

    public function updateCategory($request , $category_id){
        $category = Category::find($category_id);
        if(!$category)
        return response()->json(["message" => "category not found"], 404);
        $category->update([
            "name" => $request->name ,
        ]);
        $category->save();
        return response()->json(["category_data" => $category], 200);
    }

    public function deleteCategory($category_id){
      $category = Category::find($category_id);
      if(!$category)
      return response()->json(["message" => "category not found"], 404);
      $category->destroy($category_id);
      return response()->json(["category_data" => $category], 200);
      
    }

    public function createFeature($request , $category_id){
        $category = Category::find($category_id);
        $id = \str_pad(mt_rand(0, 9999999), 7 , '0', STR_PAD_LEFT);
        if(!$category)
        return response()->json(["message" => "catagory that contain this feature not found"], 404);
        $features = $category->features;
        foreach($features as $feature){
            if($feature['name'] == $request->name)
            return response()->json(["message" => "this feature already exists"] , 422);
        }
        $feature = ["name" => $request->name , "id" => $id];
        array_push($features , $feature);
        $category->features = $features ;
        $category->save();
        return response()->json(["feature_data" => $feature]);
    }

    public function updateFeature($request , $category_id , $feature_id){
       $category = Category::find($category_id);
       if(!$category)
       return response()->json(["message" => "catagory that contain this feature not found"], 404);
       $features = $category->features;
       foreach($features as $feature){
           if($feature['id'] == $feature_id){
               $index = array_search($feature , $features);
               $feature['name'] = $request->name;
               $features[$index] = $feature;
            }
        } 
        if(!isset($index))
        return response()->json(["message" => "feature not found"], 404);
        $category->features = $features;
        $category->save();
        return response()->json(["feature_data" => $features[$index]] , 200);
    }

    public function deleteFeature($category_id , $feature_id){
       $category = Category::find($category_id);
       if(!$category)
       return response()->json(["message" => "catagory that contain this feature not found"], 404);
       $features = $category->features ;
       foreach($features as $feature){
           if($feature['id'] == $feature_id){
               $index = array_search($feature , $features);
               $deleted_feature = $features[$index];
               unset($features[$index]);
              $features = array_values($features);
           }
        }
        if(!isset($index))
        return response()->json(["message" => "feature not found"], 404);
        $category->features = $features;
        $category->save();
        return response()->json(["feature_data" => $deleted_feature] , 200);
    }

    public function createCompany($request){
        $company = new Company();
        $company->name = $request->name ;
        $logo = time().'.'.$request->logo->extension();
        $request->logo->move(public_path('storage/company_logos'), $logo);
        $company->logo = "/company_logos/".$logo;
        $company->location =["lon" => $request->lon , "lat" => $request->lat];
        $company->email = $request->email ;
        $company->password = bcrypt($request->password) ;
        $company->phone = $request->phone ;
        $company->role_id = $request->role_id ;
        $company->products = [] ;
        $company->feedbacks = [] ;
        $company->Appointments = [] ;
        $company->rate = 0 ;
        $company->save();
        return response()->json(["company_data" => $company] , 200);   
    }

    public function updateCompany($request , $company_id){
        $company = Company::find($company_id);
        if(!$company)
        return response()->json(["message" => "company not found"], 404);
        $company->update([
            "name" => $request->name , 
            "location" => $request->location , 
            "phone" => $request->phone
        ]);
        $company->save();
        return response()->json(["company_data" => $company], 200);
    }

    public function deleteCompany($company_id){
       $company = Company::find($company_id);
       if(!$company)
       return response()->json(["message" => "comapny not found"], 404);
       File::delete(public_path('storage'.$company->logo));
       $company->destroy($company_id);
       return response()->json(["company_data" => $company], 200);

    }

    public function createCategoryDevice($request){
       $category_device = new CategoryDevice();
       $category_device->name = $request->name;
       $category_device->devices = [];
       $category_device->save();
       return response()->json(["categorydevice_data" => $category_device], 200);
    }

    public function updateCategoryDevice($request , $category_id){
       $category_device = CategoryDevice::find($category_id);
       if(!$category_device)
       return response()->json(["message" => "category device not found"] , 404);
       $category_device->update([
         "name" => $request->name ,
       ]);
       $category_device->save();
       return response()->json(["categorydevice_data" => $category_device], 200);
    }

    public function deleteCategoryDevice($category_id){
       $category_device = CategoryDevice::find($category_id); 
       if(!$category_device)
       return response()->json(["message" => "category device not found"] , 404);
       $category_device->destroy($category_id);
       return response()->json(["categorydevice_data" => $category_device] , 404);
    }

    public function createDevice($request , $categorydevice_id){
        $categorydevice = CategoryDevice::find($categorydevice_id);
        if(!$categorydevice)
        return response()->json(["message" => "category that contain this device not found"] , 404);
        $id = \str_pad(mt_rand(0, 9999999), 7 , '0', STR_PAD_LEFT);
        $devices = $categorydevice->devices;
        foreach($devices as $device){
            if($device['name'] == $request->name)
            return response()->json(["message" => "this device already exists"] , 422);
        }
        $img = time().'.'.$request->img->extension();
        $request->img->move(public_path('storage/devices_img'), $img);
        $device = ["id" => $id , "name" => $request->name ,
                   "img" => "/devices_img/".$img  , "voltage" => $request->voltage ,
                   "voltage_pouer" => $request->voltage_pouer ,
                   "number_fazes" => $request->number_fazes ,
                  ];
        array_push($devices , $device);
        $categorydevice->devices = $devices;
        $categorydevice->save();
        return response()->json(["device_data" => $device]);
    }

    public function updateDevice($request , $categorydevice_id , $device_id){
        $categorydevice = CategoryDevice::find($categorydevice_id);
        if(!$categorydevice)
        return response()->json(["message" => "catagory that contain this device not found"], 404);
        $devices = $categorydevice->devices;
        foreach($devices as $device){
            if($device['id'] == $device_id){
                $index = array_search($device , $devices);
                $device['name'] = $request->name;
                $device['voltage'] = $request->voltage;
                $device['voltage_pouer'] = $request->voltage_pouer;
                $device['number_fazes'] = $request->number_fazes;
                $devices[$index] = $device;
             }
         } 
         if(!isset($index))
         return response()->json(["message" => "device not found"], 404);
         $categorydevice->devices = $devices;
         $categorydevice->save();
         return response()->json(["device_data" => $devices[$index]] , 200);
    }

    public function deleteDevice($categorydevice_id , $device_id){
        $categorydevice = CategoryDevice::find($categorydevice_id);
        if(!$categorydevice)
        return response()->json(["message" => "catagory that contain this device not found"], 404);
        $devices = $categorydevice->devices ;
        foreach($devices as $device){
            if($device['id'] == $device_id){
                $index = array_search($device , $devices);
                File::delete(public_path('storage'.$device['img']));
                $deleted_device = $devices[$index];
                unset($devices[$index]);
               $devices = array_values($devices);
            }
         }
         if(!isset($index))
         return response()->json(["message" => "device not found"], 404);
         $categorydevice->devices = $devices;
         $categorydevice->save();
         return response()->json(["device_data" => $deleted_device] , 200);
    }

    public function get_all_users(){
      $users = User::all();
      return response()->json(["users" => $users], 200);
    }

    public function get_user_by_id($user_id){
       $user = User::find($user_id);
       return $user;
    }

    public function delete_user($user_id){
       $user = $this->get_user_by_id($user_id);
       if(!$user)
       return response()->json(["message" => "user is not exists"], 404);
       $user->delete();
       return response()->json(["user" => $user]);
    }
}