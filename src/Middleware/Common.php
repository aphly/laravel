<?php

namespace Aphly\Laravel\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Cookie;

class Common
{
    public function handle(Request $request, Closure $next)
    {
        if($request->path()=='login' || $request->path()=='register' || $request->path()=='logout'){
        }else{
            if($request->url()){
                Cookie::queue('refer', $request->url(), 1200);
            }
        }
        return $next($request);
    }

}
