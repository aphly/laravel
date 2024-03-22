<?php

namespace Aphly\Laravel\Middleware;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Cache;

class Limit
{

    public function handle(Request  $request, Closure $next)
    {
        $key = md5($request->getClientIp().$request->header('user-agent'));
        $maxAttempts = config('base.limit.maxAttempts');
        $decaySeconds = config('base.limit.decaySeconds');
        if (Cache::has($key)) {
            if (Cache::increment($key) > $maxAttempts) {
                throw new ApiException(['code'=>1,'msg'=>'You are over the limit.']);
            }
        } else {
            Cache::put($key, 1, now()->addSeconds($decaySeconds));
        }
        return $next($request);
    }
}
