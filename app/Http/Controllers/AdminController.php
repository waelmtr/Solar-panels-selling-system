<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Company;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\FeatureRequest;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\DeviceRequest;
use App\Http\Requests\UpdateDeviceRequest;
use App\Interfaces\AdminInterface;
use App\Repositories\AdminRepository;
use App\Repositories\UserRepository;


class AdminController extends Controller
{
    private AdminRepository $adminrepository;
    private UserRepository $userrepository;

    public function __construct(AdminRepository $adminrepository , UserRepository $userrepository){
      $this->middleware('auth:sanctum' ,  ['except' => ['Get_Admin_Details']]);
      $this->middleware('Admin' , ['except' => ['Get_Admin_Details']]);
      $this->adminrepository = $adminrepository ;
      $this->userrepository = $userrepository ;
    }

    public function Get_Admin_Details(){
         $admin = Admin::all();
         foreach($admin as $admin){
         $token = $admin->createToken("admintoken")->plainTextToken;
         }
         return response()->json([
           "admin" => $admin ,
           "token"=> $token ,
         ]);
    } 

    public function create_category(CategoryRequest $request){
         return $this->adminrepository->createCategory($request); 
    }  

    public function update_category(CategoryRequest $request , $category_id){
         return $this->adminrepository->updateCategory($request , $category_id);       
    }

    public function delete_category($category_id){
         return $this->adminrepository->deleteCategory($category_id);
       
    }

    public function create_features(FeatureRequest $request){
        $category_id = $request->query('category_id');
        return $this->adminrepository->createFeature($request , $category_id);
    }

    public function update_features(FeatureRequest $request , $feature_id){
        $category_id = $request->query('category_id');
        return $this->adminrepository->updateFeature($request , $category_id , $feature_id);
    }
    
    public function delete_feature(Request $request ,$feature_id){
        $category_id = $request->query('category_id');
        return $this->adminrepository->deleteFeature($category_id , $feature_id);
    } 

    public function create_company(CreateCompanyRequest $request){
        return $this->adminrepository->createCompany($request);
    }

    public function update_company(UpdateCompanyRequest $request , $company_id){
        return $this->adminrepository->updateCompany($request , $company_id);
    }

    public function delete_company($company_id){
        return $this->adminrepository->deleteCompany($company_id);
    }

    public function create_category_device(CategoryRequest $request){
        return $this->adminrepository->createCategoryDevice($request);
    }

    public function update_category_device(CategoryRequest $request , $category_id){
        return $this->adminrepository->updateCategoryDevice($request , $category_id);  
    }

    public function delete_category_device($category_id){
        return $this->adminrepository->deleteCategoryDevice($category_id);
    }

    public function create_device(DeviceRequest $request){
        $categorydevice_id = $request->query('categorydevice_id');
        return $this->adminrepository->createDevice($request , $categorydevice_id);
    }
    
    public function update_device(UpdateDeviceRequest $request , $device_id){
        $categorydevice_id = $request->query('categorydevice_id');
        return $this->adminrepository->updateDevice($request , $categorydevice_id , $device_id);
    }

    public function delete_device(Request $request , $device_id){
        $categorydevice_id = $request->query('categorydevice_id');
        return $this->adminrepository->deleteDevice($categorydevice_id , $device_id);
    }

    public function get_all_users(){
        return $this->adminrepository->get_all_users();
    }
     
    public function get_user_by_id($user_id){
        $user = $this->adminrepository->get_user_by_id($user_id);
        return response()->json(["user" => $user]);
    }

    public function delete_user($user_id){
        $this->userrepository->delete_relatedthings($user_id);
        return $this->adminrepository->delete_user($user_id);
    }


}
