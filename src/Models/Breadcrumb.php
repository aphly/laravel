<?php

namespace Aphly\Laravel\Models;

class Breadcrumb
{
    static function render($arr,$admin=true){
        $res['arr'] = self::_render($arr);
        $res['admin'] = $admin;
        return view('laravel::admin.breadcrumb', ['res' => $res]);
    }

    static function renderFront($arr){
        $res['arr'] = self::_render($arr);
        return view('laravel-front::common.breadcrumb', ['res' => $res]);
    }

    static function _render($arr){
        $new_arr = [];
        foreach ($arr as $val){
            if(empty($val['href'])){
                $val['class'] = 'no_href';
            }else{
                $val['class'] = 'href';
            }
            $new_arr[] = $val;
            $new_arr[] = ['name'=>'','class'=>'uni app-qianjin_r'];
        }
        array_pop($new_arr);
        return $new_arr;
    }

}
