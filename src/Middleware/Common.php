<?php

namespace Aphly\Laravel\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Cookie;

class Common
{
    public function handle(Request $request, Closure $next)
    {
        if($request->path()=='login' || $request->path()=='register' || $request->path()=='logout' || $request->path()=='forget'
            || $request->path()=='forget-password' || $request->is('admin/*')
        ){
        }else{
            if($request->url()){
                Cookie::queue('refer', $request->url(), 60);
            }
        }
        return $next($request);
    }

}
