<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

interface AdminInterface {
    public function createCategory($request);
    public function updateCategory($request , $category_id);
    public function deleteCategory($category_id);
    //////////////
    public function createFeature($request , $category_id);
    public function updateFeature($request , $category_id , $feature_id);
    public function deleteFeature($category_id , $feature_id);
    //////////////
    public function createCompany($request);
    public function updateCompany($request , $company_id);
    public function deleteCompany($company_id);
    //////////////
    public function createCategoryDevice($request);
    public function updateCategoryDevice($request , $category_id);
    public function deleteCategoryDevice($category_id);
    //////////////
    public function createDevice($request , $categorydevice_id);
    public function updateDevice($request , $categorydevice_id ,$device_id);
    public function deleteDevice($categorydevice_id,$device_id);
}