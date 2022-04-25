<?php

namespace Aphly\Laravel\Libs;

class Func
{
    static function array_orderby(){
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

    static function randStr($length,$seccode=false){
        if($seccode){
            $str = 'ABCDEFGHKMNPQRSTUVWXY3456789';
        }else{
            $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        }
        $len = strlen($str)-1;
        $randstr = '';
        for ($i=0;$i<$length;$i++) {
            $num=mt_rand(0,$len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }

    static function siteUrl($url){
        preg_match('/^(http(s)?:\/\/.+?)\//',$url,$matches);
        return $matches[1] ?? '';
    }

    function checkDir($fileName,$read_write = '0777'){
        $path = dirname($fileName);
        if(!file_exists($path)){
            mkdir ($path,$read_write,true);
        }
    }
}
