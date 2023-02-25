<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    private UserRepository $userrepository ;

    public function __construct(UserRepository $userrepository){
      $this->middleware('auth:sanctum' , ['except' => ['register' , 'login']]);
       $this->userrepository = $userrepository;
    }

   public function register(UserRequest $request){
     $user = $this->userrepository->register($request);
     
     $token = $user->createToken('userToken')->plainTextToken;

     return response()->json([
       "uesr" => $user ,
       "token" => $token
     ]);
   }

   public function login(LoginRequest $request){
      $user = $this->userrepository->login($request);
      return $user;
   }

   public function logout(){
    $this->userrepository->logout();
    return response()->json(["message" => "you are loging out"] , 200);
   }
   
   public function delete_account(Request $request){
    $request->validate(['password' => 'required']);
    return $this->userrepository->DeleteAccount($request);
   }

}
