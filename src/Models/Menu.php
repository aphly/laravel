<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    use HasFactory;
    protected $table = 'admin_menu';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'url',
        'pid',
        'icon',
        'is_leaf',
        'status',
        'sort','level_id','module_id'
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
