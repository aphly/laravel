<?php

namespace Aphly\Laravel\Middleware;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\RoleMenu;
use Aphly\Laravel\Models\RolePermission;
use Illuminate\Http\Request;
use Closure;

class Rbac
{
    public $ignore_url = [];

    public function handle(Request $request, Closure $next)
    {
        if( !$this->checkPermission( $request->route()->uri ) ){
            throw new ApiException('没有权限');
        }
        return $next($request);
    }

    public function checkPermission( $uri ){
        if( in_array( $uri,$this->ignore_url ) ){
            return true;
        }
        $role_id = session('role_id');
        if($role_id){
            if($role_id == 1){
                return true;
            }
            $roleMenu = RoleMenu::leftJoin('admin_menu','admin_menu.id','=','admin_role_menu.menu_id')->where('admin_role_menu.role_id',$role_id)
                ->where('admin_menu.status',1)->get()->toArray();
            if($roleMenu){
                $menu_route = array_filter(array_column($roleMenu,'route'));
                if(in_array($uri,$menu_route)){
                    return true;
                }
            }
            $rolePermission = RolePermission::leftJoin('admin_permission','admin_permission.id','=','admin_role_permission.permission_id')
                ->where('admin_role_permission.role_id',$role_id)
                ->where('admin_permission.status',1)->get()->toArray();
            if($rolePermission){
                $permission_route = array_filter(array_column($rolePermission,'route'));
                if(in_array($uri,$permission_route)){
                    return true;
                }
            }
        }
        return false;
    }

}
