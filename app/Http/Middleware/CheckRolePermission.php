<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;

class CheckRolePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $sec, $type)
    {

        $role_id=auth()->user()->role; 
     
        $permission = DB::table('users')->join('permissions', 'permissions.role_id', '=', 'users.role')->where('role', $role_id)
        ->where('section', $sec)->first();
         if(!empty($permission)){
        if ($permission->$type=='yes') {            
            return $next($request); 
        }else{
            return redirect()->route('unauthorized')->with('message', 'Unauthorized Access');
        //   abort(403, 'Unauthorized');  
        }
         }
         return redirect()->route('unauthorized')->with('message', 'Unauthorized Access');
        // abort(403, 'Unauthorized');
       
    }
}
