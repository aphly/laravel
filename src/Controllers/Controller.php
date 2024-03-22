<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class Controller extends \App\Http\Controllers\Controller
{

    function makeView($template,$data){
        if(Request::wantsJson()){
            return response()->json($data);
        }
        $template = $this->existView($template);
        if($template){
            return view($template, $data);
        }else{
            throw new ApiException(['code'=>1,'msg'=>'view does not exist ']);
        }
    }

    function existView($template)
    {
        //$template = 'laravel-front::account.forget';
        if(View::exists($template)){
            return $template;
        }else{
            $template = str_replace('laravel-front','laravel-front-base',$template);
            if(View::exists($template)){
                return $template;
            }else{
                return false;
            }
        }
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
