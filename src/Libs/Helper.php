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

    static function findAllFiles($dir,$type='curr'): array{
        $root = scandir($dir);
        $result = [];
        foreach($root as $value){
            if($value === '.' || $value === '..'){
                continue;
            }
            if(is_file("$dir/$value")){
                if($type=='curr'){
                    $result[] = $value;
                //}else{
                    //$curr_dir = str_replace($cdir,'',$dir);
                    //$result[] = "$value";
                }
                continue;
            }else{
                if($type!='curr'){
                    foreach(self::findAllFiles("$dir/$value",$type) as $v){
                        $result[] = "$value/$v";
                    }
                }
            }
        }
        return $result;
    }

    static function getTree($array): array{
        $new_array = [];
        foreach($array as $v){
            $new_array[$v['id']] = $v;
        }
        $return_tree = [];
        foreach($new_array as $kk=>$vv){
            if(isset($new_array[$vv['pid']])){
                $new_array[$vv['pid']]['child'][] = &$new_array[$kk];
            }else{
                $return_tree[] = &$new_array[$kk];
            }
        }
        return $return_tree;
    }
}
