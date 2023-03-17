<?php

namespace Aphly\Laravel\Middleware;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\LaravelAdmin\Models\Role;
use Aphly\LaravelAdmin\Models\RolePermission;
use Aphly\LaravelAdmin\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class Rbac
{
    public $ignore_url = [];

    public function handle(Request $request, Closure $next)
    {
        if( !$this->checkPermission( $request->route()->getAction()['controller'] ) ){
            throw new ApiException(['code'=>1,'msg'=>'没有权限']);
        }
        return $next($request);
    }

    public function checkPermission( $controller ){
        if(Auth::guard('manager')->user()->super==1){
            return true;
        }
        if( in_array( $controller,$this->ignore_url ) ){
            return true;
        }
        list($class) = explode('@',$controller);
        return in_array( $controller, (new Role)->getRolePermission()) || in_array( $class, (new Role)->getRolePermission());
    }

    public function getRolePermission_bf(){
        $role_ids = UserRole::where([ 'uuid' => Auth::guard('manager')->user()->uuid ])->select('role_id')->get()->toArray();
        $role_ids = array_column($role_ids,'role_id');
        $permission = RolePermission::whereIn('role_id',$role_ids)->with('permission')->get()->toArray();
        $has_permission = [];
        foreach ($permission as $v){
            $has_permission[$v['permission']['id']] = $v['permission']['controller'];
        }
        return $has_permission;
    }

}
