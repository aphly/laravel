<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;
use Illuminate\Support\Facades\Cache;

class Permission extends Model
{
    use HasFactory;
    protected $table = 'admin_permission';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'controller',
        'pid',
        'is_leaf',
        'status',
        'sort','module_id'
    ];
    public function getPermissionById($id)
    {
        return Cache::rememberForever('permission_'.$id, function () use ($id) {
            $res['permission'] = Permission::where('status', 1)->orderBy('sort', 'desc')->get()->toArray();
            $res['permission_tree'] = Helper::getTree($res['permission'], true);
            Helper::getTreeByid($res['permission_tree'], $id, $res['permission_tree']);
            Helper::TreeToArr([$res['permission_tree']], $res['permission_show']);
            return $res['permission_show'];
        });
    }


//    protected static function boot()
//    {
//        parent::boot();
//        static::deleted(function (User $user) {
//            UserUni::destroy($user->id);
//            UserInfo::destroy($user->id);
//            self::delAvatar($user->avatar);
//        });
//    }
}
