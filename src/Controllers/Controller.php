<?php

namespace Aphly\Laravel\Controllers;

use Aphly\LaravelAdmin\Models\Dict;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class Controller extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        View::share("dict",(new Dict)->getByKey());
    }

    function makeView($template,$data){
        if(Request::wantsJson()){
            return response()->json($data);
        }
        return view($template, $data);
    }

    function limiter($key,$limit=0){
        $num = Cache::get($key, 0);
        if($limit){
            return $num<$limit?true:false;
        }else{
            return $num;
        }
    }

    function limiterIncrement($key,$sec){
        if (Cache::has($key)) {
            Cache::increment($key, 1);
        }else{
            Cache::put($key, 1, $sec);
        }
    }

}
