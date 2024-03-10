<?php

namespace Aphly\Laravel\Middleware;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class UserAuth
{
    public function handle(Request $request, Closure $next)
    {
        $auth = Auth::guard('user');
        if($request->url()==route('login') || $request->url()==route('register')){
            if ($auth->check()) {
                $this->userStatus($auth);
                throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>url('')]]);
            }else{
                return $next($request);
            }
        }else{
            if ($auth->check()) {
                $this->userStatus($auth);
                return $next($request);
            }else{
                $redirect = $request->query('redirect',false);
                if($redirect){
                    throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('login',['redirect'=>$redirect])]]);
                }
                throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('login')]]);
            }
        }
    }

    public function userStatus($auth){
        if($auth->user()->status==2) {
            throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('blocked')]]);
        }else if(config('base.email_verify') && !($auth->user()->verified)){
            throw new ApiException(['code'=>0,'msg'=>'redirect','data'=>['redirect'=>route('emailVerify')]]);
        }
    }


}
