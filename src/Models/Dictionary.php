<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Dictionary extends Model
{
    use HasFactory;
    protected $table = 'dictionary';
    public $timestamps = false;
    protected $fillable = [
        'name','sort','pid','status','is_leaf','json','icon','value'
    ];

    public function getDictionaryById($id)
    {
        return Cache::rememberForever('dictionary_'.$id, function () use ($id) {
            $res['dictionary_tree'] = $this->getDictionaryTreeById($id);
            Helper::TreeToArr([$res['dictionary_tree']],$res['dictionary_show']);
            return $res['dictionary_show'];
        });
    }

    public function getDictionaryTreeById($id)
    {
        return Cache::rememberForever('dictionary_'.$id.'_tree', function () use ($id) {
            $res['dictionary'] = self::where('status',1)->orderBy('sort', 'desc')->get()->toArray();
            foreach ($res['dictionary'] as $key=>$val){
                $res['dictionary'][$key]=$val;
                if($val['json']){
                    $arr = json_decode($val['json'], true);
                    $new_arr = [];
                    foreach ($arr as $k=>$v) {
                        $new_arr[$v['group']][$k] = $v;
                    }
                    $res['dictionary'][$key]['json'] = $new_arr;
                }
            }
            $res['dictionary_tree'] = Helper::getTree($res['dictionary'],true);
            Helper::getTreeByid($res['dictionary_tree'],$id,$res['dictionary_tree']);
            return $res['dictionary_tree'];
        });
    }


}
