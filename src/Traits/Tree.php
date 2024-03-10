<?php

namespace Aphly\Laravel\Traits;

use Aphly\Laravel\Libs\Helper;

trait Tree
{
    public function getChildIds($id){
        $all = self::get()->toArray();
        $tree = Helper::getTree($all,true);
        Helper::getTreeByid($tree,$id,$tree);
        Helper::TreeToArr([$tree],$res);
        return array_column($res,'id');
    }

    public function closeChildStatus($id){
        $ids = $this->getChildIds($id);
        if(count($ids)>1){
            $this->whereIn('id',$ids)->update(['status'=>2]);
        }
    }

}
