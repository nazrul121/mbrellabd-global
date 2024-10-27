<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $permission_origin)
    {
        if(Auth::user()->user_type_id ==3){
            $permission_id = \DB::table('permissions')->where('origin',$permission_origin)->pluck('id')->first();
            if(request()->get('permissionType')=='role-base'){
                $isPermitted = \DB::table('permission_user_type')->where(['user_type_id'=>Auth::user()->user_type_id,'permission_id'=>$permission_id]);
            }else{
                $isPermitted = \DB::table('permission_user')->where(['user_id'=>Auth::user()->id,'permission_id'=>$permission_id]);
            }
            // dd($isPermitted->get());
            if($permission_id !=null && $isPermitted->count() >0)  return $next($request);
            else return redirect()->route('common.no-access');
        }else  return $next($request);

    }

}
