<?php

namespace Aphly\Laravel\Middleware;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class ManagerAuth
{
    public function handle(Request $request, Closure $next)
    {
        $auth = Auth::guard('manager');
        if($request->url() == route('adminLogin')){
            if ($auth->check()) {
                $this->managerStatus($auth);
                throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('adminIndex')]]);
            }else{
                return $next($request);
            }
        }else{
            if ($auth->check()) {
                $this->managerStatus($auth);
                return $next($request);
            }else{
                throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('adminLogin')]]);
            }
        }
    }

    public function managerStatus($auth){
        if($auth->user()->status==2) {
            throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('adminBlocked')]]);
        }else if($auth->user()->status==3){
            throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('waitCheck')]]);
        }
    }

}
