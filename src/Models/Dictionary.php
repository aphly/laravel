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
        'name','sort','pid','status','is_leaf','json','icon'
    ];

    public function getMenuById($id)
    {
        return Cache::rememberForever('dictionary_'.$id, function () use ($id) {
            $res['dictionary'] = self::where('status',1)->orderBy('sort', 'desc')->get()->toArray();
            $res['dictionary_tree'] = Helper::getTree($res['dictionary'],true);
            Helper::getTreeByid($res['dictionary_tree'],$id,$res['dictionary_tree']);
            Helper::TreeToArr([$res['dictionary_tree']],$res['dictionary_show']);
            return $res['dictionary_show'];
        });
    }
}
