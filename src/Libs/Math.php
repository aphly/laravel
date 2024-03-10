<?php

namespace Aphly\Laravel\Libs;


class Math
{
    static function add($n1,$n2,$s=2){
        return floatval(bcadd($n1,$n2,$s));
    }

    static function sub($n1,$n2,$s=2){
        return floatval(bcsub($n1,$n2,$s));
    }

    static function mul($n1,$n2,$s=2){
        return floatval(bcmul($n1,$n2,$s));
    }

    static function div($n1,$n2,$s=2){
        return floatval(bcdiv($n1,$n2,$s));
    }
}
