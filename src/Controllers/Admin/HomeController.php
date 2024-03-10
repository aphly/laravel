<?php

namespace Aphly\Laravel\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Comm;
use Aphly\Laravel\Models\RoleMenu;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Aphly\Laravel\Libs\Helper;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{

    public function layout()
    {
        $res['title'] = '管理中心';
        $role_id = session('role_id');
        if($role_id){
            $comm = Comm::where('host',config('base.local_host'))->with(['module' => function ($query) {
                $query->where('status',1);
            }])->first();
            $module_ids = $comm->module->pluck('id')->toArray();
            $res['menu'] = (new RoleMenu)->getMenu($role_id,$module_ids);
            $res['menu_tree'] = Helper::getTree($res['menu'],true);
            $res['user'] = Auth::guard('manager')->user();
            return $this->makeView('laravel::admin.layout',['res'=>$res]);
        }else{
            throw new ApiException(['code'=>0,'msg'=>'choose role','data'=>['redirect'=>route('adminRole')]]);
        }
    }

    public function cache()
    {
        Cache::flush();
        Artisan::call('view:clear');
        throw new ApiException(['code'=>0,'msg'=>'缓存已清空','data'=>['redirect'=>route('adminIndex')]]);
    }


}
