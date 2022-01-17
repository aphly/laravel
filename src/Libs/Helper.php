<?php

namespace Aphly\Laravel\Libs;

class Helper
{
    static public function is_today($time){
        if(date('Y-m-d') == date('Y-m-d',$time)){
            return true;
        }else{
            return false;
        }
    }

    static function is_phone($mobile){
        if(preg_match("/^1\d{10}$/", $mobile)){
            return true;
        }else{
            return false;
        }
    }
}
