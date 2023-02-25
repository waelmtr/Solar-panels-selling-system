<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Repositories\ProductRepository;
use Auth;

class ProductController extends Controller
{
    private ProductRepository $productrepository;
    public function __construct(ProductRepository $productrepository){
      $this->middleware('auth:sanctum');
      $this->middleware('product');
      $this->productrepository = $productrepository;
    } 

    public function check_company($company_id){
      $company = Company::find($company_id);
      if(!$company)
      return response()->json(["message" => "company not found"]);
      return $company; 
    }

    public function create_product(Request $request){
       $company_id = $request->query('company_id');
       if(!$company_id)
       $company_id = auth()->id();
       $company = $this->check_company($company_id);
       if(is_array(json_decode($company , true))){
        $category_id = $request->query('category_id');
        return $this->productrepository->create_product($request , $company , $category_id);
       }
       return $company;
    }

    public function update_product(Request $request , $product_id){
        $company_id = $request->query('company_id');
        if(!$company_id)
        $company_id = auth()->id();
        $company = $this->check_company($company_id);
        if(is_array(json_decode($company , true))){
         $category_id = $request->query('category_id');
         return $this->productrepository->update_product($request , $company , $category_id , $product_id);
        }
        return $company;
    }

    public function delete_product(Request $request , $product_id){
        $company_id = $request->query('company_id');
        if(!$company_id)
        $company_id = auth()->id();
        $company = $this->check_company($company_id);
        if(is_array(json_decode($company , true))){
         $category_id = $request->query('category_id');
         return $this->productrepository->delete_product($company , $category_id , $product_id);
        }
        return $company;
    }

    public function get_products(Request $request){
      return $this->productrepository->get_products($request);
    }

    public function create_features(Request $request , $product_id){
      $company_id = $request->query('company_id');
      if(!$company_id)
      $company_id = auth()->id();
      $company = $this->check_company($company_id);
      if(is_array(json_decode($company , true))){
       $category_id = $request->query('category_id');
       return $this->productrepository->create_features($request , $company , $category_id , $product_id);
      }
      return $company;
    }

    public function update_features(Request $request , $product_id , $feature_id){
      $company_id = $request->query('company_id');
      if(!$company_id)
      $company_id = auth()->id();
      $company = $this->check_company($company_id);
      if(is_array(json_decode($company , true))){
       $category_id = $request->query('category_id');
       return $this->productrepository->update_features($request , $company , $category_id , $product_id , $feature_id);
      }
      return $company;
    } 

    public function delete_feature(Request $request , $product_id , $feature_id){
      $company_id = $request->query('company_id');
      if(!$company_id)
      $company_id = auth()->id();
      $company = $this->check_company($company_id);
      if(is_array(json_decode($company , true))){
       $category_id = $request->query('category_id');
       return $this->productrepository->delete_feature($company , $category_id , $product_id , $feature_id);
      }
      return $company;
    }
}
