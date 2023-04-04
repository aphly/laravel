<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cookie;

class ManagerRole extends Model
{
    use HasFactory;
    protected $table = 'admin_manager_role';
    public $timestamps = false;
    protected $fillable = [
        'uuid',
        'role_id',
    ];

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

//    public $active_role_id = 0;
//
//    function activeRole(){
//        $active_role_id = Cookie::get('active_role_id');
//
//    }
//
//    public function getMenu(): array
//    {
//        $active_role_id = Cookie::get('active_role_id');
//        $role_ids = self::where([ 'uuid' => Manager::_uuid(),'role_id'=>$active_role_id])->select('role_id')->get()->toArray();
//        $role_ids = array_column($role_ids,'role_id');
//        $role_menu = $this->role_menu_cache();
//        $has_menu = [];
//        foreach($role_ids as $id){
//            if(isset($role_menu[$id])){
//                foreach ($role_menu[$id] as $k=>$v){
//                    $has_menu[$k] = $v;
//                }
//            }
//        }
//        return $has_menu;
//    }

    public function hasLevelIds($uuid){
        $data = self::where('uuid',$uuid)->get()->toArray();
        $role_ids = array_column($data,'role_id');
        return (new Role)->hasLevelIds($role_ids);
    }
}
