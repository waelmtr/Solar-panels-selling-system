<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{

  public function get_company_by_id($company_id){
     $company = Company::find($company_id);
     if(!$company)
     return response()->json(["message" => "company not found"], 404);
     return response()->json(["company_data" => $company->rate], 200);
  } 

  public function get_all_companies(Request $request){
    $name = $request->query('name');
    $companies = Company::all();
    if(!$name)
    return response()->json(["companies" => $companies], 200);
    foreach($companies as $company){
     $company = $company->where('name' , 'like' ,'%'.$name.'%' )->get();
     if($company->isEmpty())
     return response()->json(["message" => "this comapny not fount"]);
     return response()->json(["companies" => $company] , 200);
    }
  }
  
}
