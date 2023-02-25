<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CategoryDeviceController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProductController;





use App\Models\Role;
use App\Models\Category;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//// auth ////

Route::group(['prefix'=>'user'] , function(){
    Route::post('/register' , [UserController::class , 'register']);
    Route::post('/login' , [UserController::class , 'login']);
    Route::get('/logout' , [UserController::class , 'logout']);
    Route::post('/delete' , [UserController::class , 'delete_account']);
});

Route::group(['prefix'=>'admin'] , function(){
    Route::get('/all_users' , [AdminController::class , 'get_all_users']);
    Route::get('/show/{user_id}' , [AdminController::class , 'get_user_by_id']);
    Route::delete('/delete_user/{user_id}' , [AdminController::class , 'delete_user']);
});
///role ////
Route::post('/create_role' , [RoleController::class , 'create_role']);

//// Category Routes /////
Route::group(['prefix'=>'category'] , function(){
    Route::post('/create' , [AdminController::class , 'create_category']);
    Route::post('/update/{category_id}' , [AdminController::class , 'update_category']);
    Route::delete('/delete/{category_id}' , [AdminController::class , 'delete_category']);
    Route::get('/show/{category_id}' , [CategoryController::class , 'get_category_by_id']);
    Route::get('/all_categories' , [CategoryController::class , 'get_all_categories']); /// with by name
});
//// Feature Route ////
Route::group(['prefix'=>'feature'] , function(){
    Route::post('/create' , [AdminController::class , 'create_features']);
    Route::post('/update/{feature_id}' , [AdminController::class , 'update_features']);
    Route::delete('/delete/{feature_id}' , [AdminController::class , 'delete_feature']);
    Route::get('/show/{feature_id}' , [FeatureController::class , 'get_feature_by_id']);
    Route::get('/all_features' , [FeatureController::class , 'get_all_features_by_catigory_id']);/// with by name 
});
//// Company Route ////
Route::group(['prefix'=>'company'] , function(){
    Route::post('/create' , [AdminController::class , 'create_company']);
    Route::post('/update/{company_id}' , [AdminController::class , 'update_company']);
    Route::delete('/delete/{company_id}' , [AdminController::class , 'delete_company']);
    Route::get('/show/{company_id}' , [CompanyController::class , 'get_company_by_id']);
    Route::get('/all_companies' , [CompanyController::class , 'get_all_companies']);/// with by name 
});
//// Category Device Route ////
Route::group(['prefix'=>'category_device'] , function(){
    Route::post('/create' , [AdminController::class , 'create_category_device']);
    Route::post('/update/{category_id}' , [AdminController::class , 'update_category_device']);
    Route::delete('/delete/{category_id}' , [AdminController::class , 'delete_category_device']);
    Route::get('/show/{category_id}' , [CategoryDeviceController::class , 'get_categorydevice_by_id']);
    Route::get('/all_categorydevices' , [CategoryDeviceController::class , 'get_all_categorydevices']);/// with by name 
});
//// Devices Router /////

Route::group(['prefix'=>'device'] , function(){
    Route::post('/create' , [AdminController::class , 'create_device']);
    Route::post('/update/{device_id}' , [AdminController::class , 'update_device']);
    Route::delete('/delete/{device_id}' , [AdminController::class , 'delete_device']);
    Route::get('/show/{device_id}' , [DeviceController::class , 'get_device_by_id']);
    Route::get('/all_devices' , [DeviceController::class , 'get_all_devices_by_catigory_id']);/// with by name 
});
//// Feedbacks Route ////
Route::group(['prefix'=>'feedback'] , function(){
    Route::post('/create' , [FeedbackController::class , 'create_feedback']);
    Route::post('/update/{feedback_id}' , [FeedbackController::class , 'update_feedback']);
    Route::delete('/delete/{feedback_id}' , [FeedbackController::class , 'delete_feedback']);
    Route::get('/all_feedbacks' , [FeedbackController::class , 'get_all_feedback_by_company_id']); 
});

Route::group(['prefix'=>'product'] , function(){
    Route::post('/create' , [ProductController::class , 'create_product']);
    Route::post('/update/{product_id}' , [ProductController::class , 'update_product']);
    Route::delete('/delete/{product_id}' , [ProductController::class , 'delete_product']);
    Route::get('/search' , [ProductController::class , 'get_products']); 
    Route::post('/{product_id}/create_feature' , [ProductController::class , 'create_features']);
    Route::post('/{product_id}/update_feature/{feature_id}' ,[ProductController::class , 'update_features']);
    Route::delete('{product_id}/delete_feature/{feature_id}' , [ProductController::class , 'delete_feature']);
});



Route::get('/admin_details' , [AdminController::class , 'Get_Admin_Details']);



