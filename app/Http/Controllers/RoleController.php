<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function create_role(Request $request){
       $role = new Role();
       $role->roleid = $request->roleid ;
       $role->role = $request->role ;
       $role->save();

       return response()->json([
         "role" => $role
       ]);
    } 
}
