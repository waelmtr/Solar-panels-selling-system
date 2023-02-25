<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\Admin;
use App\Models\Team;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Auth;


class UserRepository implements UserInterface {


    public function delete_relatedthings($user_id){
      $companies = Company::all();
      foreach($companies as $company){
        $feedbacks = $company->feedbacks;
          if(count($feedbacks)>0){
            foreach($feedbacks as $feedback){
              if($feedback['user_id'] == $user_id){
                $index = array_search($feedback , $feedbacks);
                $deleted_feedback = $feedbacks[$index];
                unset($feedbacks[$index]);
                $feedbacks = array_values($feedbacks);
              }
            }
          }
        $company->feedbacks = $feedbacks;
        $company->save();
      }
      return ;
    }
    
    public function register($request){
       $user = new User();
       $user->name = $request->name;
       $user->location = ["lon" => $request->lon , "lat" => $request->lat];
       $user->email = $request->email;
       $user->password = bcrypt($request->password);
       $user->phone = $request->phone;
       $user->role_id = $request->role_id;
       $user->notifications = [];
       $user->orders = [];
       $user->save();

       return $user;

    }
    
    public function login($request){
        $role_id = $request->role_id;
        $roles = Role::all();

        foreach($roles as $role){
          $role = $role->where('roleid' , $role_id)->get();
          switch($role[0]->role){
            case "AsUser" : 
              $users = User::all();
             foreach($users as $user){
                $user = $user->where('email' , $request->email)->first();
                if(!$user || !Hash::check($request->password, $user->password ))
                return response()->json([
                  "message" => "email or passwored is uncorrect"
                ] , 422 );
                $token = $user->createToken('userToken')->plainTextToken;
                return response()->json([
                  "uesr" => $user ,
                  "token" => $token
                ]);
              }
            break;
            case "AsAdmin": 
                $admins = Admin::all();
                foreach($admins as $admin){
                   $admin = $admin->where('email' , $request->email)->first();
                   if(!$admin || !Hash::check($request->password, $admin->password ))
                    return response()->json([
                     "message" => "email or passwored is uncorrect"
                    ] , 422);
                   $token = $admin->createToken('adminToken')->plainTextToken;
                   return response()->json([
                     "admin" => $admin ,
                     "token" => $token
                   ]);
                }
            break;  
            case "AsTeam":
                $teams = Team::all();
                foreach($teams as $team){
                   $team = $team->where('email' , $request->email)->first();
                   if(!$team || !Hash::check($request->password, $team->password ))
                   return response()->json([
                     "message" => "email or passwored is uncorrect"
                   ] , 422);
                   $token = $team->createToken('teamToken')->plainTextToken;
                   return response()->json([
                     "team" => $team ,
                     "token" => $token
                   ]);
                }
            break;
            case "AsCompany":
                $companies = Company::all();
                foreach($companies as $company){
                   $company = $company->where('email' , $request->email)->first();
                   if(!$company || !Hash::check($request->password, $company->password ))
                   return response()->json([
                     "message" => "email or passwored is uncorrect"
                   ]);
                   $token = $company->createToken('companyToken')->plainTextToken;
                   return response()->json([
                     "company" => $company ,
                     "token" => $token
                   ] , 422);
                }
            break;    
            default :
            return response()->json([
               "message" => "you should register first"
            ]);

          }
      }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
    }
     
    public function DeleteAccount($request){
      $user = auth()->user();
      if(!Hash::check($request->password, $user->password)){
        return response()->json(["message" => "password is not correct"] , 422);
      }
      $user_id = $user->id;
      $this->delete_relatedthings($user_id);
      $user->delete();
      return response()->json(["user" => $user]);
    }

}
   







