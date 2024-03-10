<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Role extends Model
{
    use HasFactory;
    protected $table = 'admin_role';
    public $timestamps = false;

    const reg_id = 3;

    protected $fillable = [
        'name','desc','status','sort','module_id','data_perm','level_id','uuid'
    ];

    public function api()
    {
        return $this->belongsToMany(Api::class,'admin_role_api','role_id','api_id');
    }

    public function menu()
    {
        return $this->belongsToMany(Menu::class,'admin_role_menu','role_id','menu_id');
    }

    public function level()
    {
        return $this->hasOne(level::class, 'id', 'level_id');
    }

    public function getMenu(): array
    {
        $role_ids = ManagerRole::where([ 'uuid' => Manager::_uuid()])->select('role_id')->get()->toArray();
        $role_ids = array_column($role_ids,'role_id');
        $role_menu = $this->role_menu_cache();
        $has_menu = [];
        foreach($role_ids as $id){
            if(isset($role_menu[$id])){
                foreach ($role_menu[$id] as $k=>$v){
                    $has_menu[$k] = $v;
                }
            }
        }
        return $has_menu;
    }

    public function role_menu_cache(){
        return Cache::rememberForever('role_menu', function () {
            $menu = RoleMenu::leftJoin('admin_menu','admin_menu.id','=','admin_role_menu.menu_id')->where('admin_menu.status',1)->orderBy('admin_menu.sort','desc')->get()->toArray();
            $role_menu = [];
            foreach ($menu as $v) {
                $role_menu[$v['role_id']][$v['menu_id']] = $v;
            }
            return $role_menu;
        });
    }

    public function getRoleById($id){
        return Cache::rememberForever('role_'.$id, function () use ($id) {
            $res['role'] = Role::where('status',1)->orderBy('sort', 'desc')->get()->toArray();
            $res['role_tree'] = Helper::getTree($res['role'],true);
            Helper::getTreeByid($res['role_tree'],$id,$res['role_tree']);
            Helper::TreeToArr([$res['role_tree']],$res['role_show']);
            return $res['role_show'];
        });
    }

    public function hasLevelIds($role_id){
        $level_ids = [];
        $info = self::where('id',$role_id)->firstToArray();
        if($info){
            if($info['data_perm']==3){
                $levelPath = (new LevelPath)->hasAll($info['level_id']);
                foreach ($levelPath as $v){
                    $level_ids[] = $v['level_id'];
                }
            }else if($info['data_perm']==2){
                $level_ids[] = $info['level_id'];
            }
        }
        return $level_ids;
    }
}
