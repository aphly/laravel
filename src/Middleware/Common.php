<?php

namespace Aphly\Laravel\Middleware;

use Aphly\LaravelAdmin\Models\Banned;
use Illuminate\Http\Request;
use Closure;

class Common
{
    public function handle(Request $request, Closure $next)
    {
        if((new Banned)->isExist($request->ip())){
            if($request->url() == route('banned')){
                return $next($request);
            }else{
                return redirect()->route('banned');
            }
        }else{
            return $next($request);
        }
    }

}
