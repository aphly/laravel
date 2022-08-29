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

    static function uuid()
    {
        //return md5(uniqid(mt_rand(), true));
        return (new Snowflake)->id();
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

    static function getTree($array,$sort=false): array{
        $return_tree = [];
        if($array){
            $new_array = [];
            foreach($array as $v){
                $new_array[$v['id']] = $v;
            }
            foreach($new_array as $k=>$v){
                if(isset($new_array[$v['pid']])){
                    $new_array[$v['pid']]['child'][] = &$new_array[$k];
                    if($sort){
                        $sort = array_column($new_array[$v['pid']]['child'],'sort');
                        array_multisort($sort,SORT_DESC,SORT_NUMERIC,$new_array[$v['pid']]['child']);
                    }
                }else{
                    $return_tree[] = &$new_array[$k];
                }
            }
        }
        return $return_tree;
    }

    static function getTreeByid($tree,$id,&$res){
        foreach($tree as $v){
            if($v['id']==$id){
                $res=$v;
                break;
            }else{
                if(isset($v['child'])){
                    self::getTreeByid($v['child'],$id,$res);
                }
            }
        }
    }

    static function TreeToArr($tree,&$res){
        foreach ($tree as $v) {
            if (isset($v['child'])) {
                $next = $v['child'];
                unset($v['child']);
                $res[] = $v;
                self::TreeToArr($next, $res);
            }else{
                $res[] = $v;
            }
        }
    }

    static function getParentByPid($arr,$pid){
        $res = [];
        self::_getParentByPid($arr,$pid,$res);
        return array_reverse($res);
    }

    static function _getParentByPid($arr,$pid,&$return){
        foreach ($arr as $key => $value){
            if($value['id']==$pid){
                $return[]=['id'=>$value['id'],'name'=>$value['name']];
                if(intval($value['pid'])){
                    self::_getParentByPid($arr,$value['pid'],$return);
                }
            }
        }
    }

}
