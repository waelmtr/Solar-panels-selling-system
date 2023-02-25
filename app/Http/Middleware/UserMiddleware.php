<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Role;
use Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $roles = Role::all();
        foreach($roles as $role){
            if($role->roleid == auth()->user()->role_id){
                if($role->role == 'AsUser')
                return $next($request);

                else
                return response()->json([
                    "message"=>"you don't have a permission to access to this service"
                ]);
            }
        }  
    }
}
