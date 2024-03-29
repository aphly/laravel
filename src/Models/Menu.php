<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Aphly\Laravel\Traits\Tree;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory,Tree;
    protected $table = 'admin_menu';
    public $timestamps = false;

    protected $fillable = [
        'name','route','pid','icon','type',
        'status','sort','module_id','uuid'
    ];

    public function getMenuById($id)
    {
        return Cache::rememberForever('menu_'.$id, function () use ($id) {
            $res['menu'] = self::where('status',1)->orderBy('sort', 'desc')->get()->toArray();
            $res['menu_tree'] = Helper::getTree($res['menu'],true);
            Helper::getTreeByid($res['menu_tree'],$id,$res['menu_tree']);
            Helper::TreeToArr([$res['menu_tree']],$res['menu_show']);
            return $res['menu_show'];
        });
    }


}
