<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use App\Models\Company;
use Auth;

class FeedbackController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum' , ['except' => ['get_all_feedback_by_company_id']]);
        $this->middleware('User' ,  ['except' => ['get_all_feedback_by_company_id' , 'delete_feedback']]);
        $this->middleware('delete_feedback' , ['only' => ['delete_feedback']]);
    }

    public function create_feedback(FeedbackRequest $request){
      $company_id = $request->query('company_id');
      $id = \str_pad(mt_rand(0, 9999999), 7 , '0', STR_PAD_LEFT);
      $user = auth()->user();
      $company = Company::find($company_id);
      if(!$company)
      return response()->json(["message"=>"this company not found"] , 404);
      $company_feedbacks = $company->feedbacks;
      $feedback = ["id" => $id , "message" => $request->message , "user_id" => $user->id];
      array_push($company_feedbacks , $feedback);
      $company->feedbacks = $company_feedbacks;
      $company->save();
      return response()->json(["feedback" => $feedback ], 200);
    }

    public function update_feedback(FeedbackRequest $request , $feedback_id){
       $company_id = $request->query('company_id');
       $company = Company::find($company_id);
       if(!$company)
       return response()->json(["message"=>"this company not found"] , 404);
       $feedbacks = $company->feedbacks;
       foreach($feedbacks as $feedback){
         if($feedback['id']==$feedback_id){
            $index = array_search($feedback , $feedbacks);
            $feedback['message'] = $request->message;
            $feedbacks[$index] = $feedback ;
          }
        }
       if(!isset($index))
       return response()->json(["message" => "feedback not found"], 404);
       $company->feedbacks = $feedbacks;
       $company->save();
       return response()->json(["feedback" => $feedbacks[$index]] , 200);
    }

    public function delete_feedback(Request $request , $feedback_id){
        $company_id = $request->query('company_id');
        $company = Company::find($company_id);
        if(!$company)
        return response()->json(["message" => "company that contain this feedback not found"], 404);
        $feedbacks = $company->feedbacks ;
        foreach($feedbacks as $feedback){
            if($feedback['id'] == $feedback_id){
                $index = array_search($feedback , $feedbacks);
                $deleted_feedback = $feedbacks[$index];
                unset($feedbacks[$index]);
               $feedbacks = array_values($feedbacks);
            }
        }
         if(!isset($index))
         return response()->json(["message" => "feedback not found"], 404);
         $company->feedbacks = $feedbacks;
         $company->save();
         return response()->json(["feedback" => $deleted_feedback] , 200);
    }

    public function get_all_feedback_by_company_id(Request $request){
        $company_id = $request->query('company_id');
        $company = Company::find($company_id);
        if(!$company)
        return response()->json(["message" => "company not found"] , 404);
        $feedbacks = $company->feedbacks;
        if(count($feedbacks)==0)
        return response()->json(["feedback" => "no feedback"] , 200);
        return response()->json(["feedback" => $feedbacks] , 200);
    }
}
