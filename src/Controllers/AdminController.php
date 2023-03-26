<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Models\Dict;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{

    public $manager = null;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $auth = Auth::guard('manager');
            if($auth->check()){
                $this->manager = $auth->user();
                View::share("manager",$this->manager);
            }else{
                View::share("manager",[]);
            }
            View::share("dict",(new Dict)->getByKey());
            date_default_timezone_set('Asia/Shanghai');
            return $next($request);
        });
    }

    public function index_url($post): string
    {
        if (!empty($post['pid'])) {
            return $this->index_url . '?pid=' . $post['pid'];
        } else {
            return $this->index_url;
        }
    }

    public function uninstall(){
        $admin_menu = DB::table('base_menu')->where('module_id',$this->module_id);
        $arr = $admin_menu->get()->toArray();
        if($arr){
            $admin_menu->delete();
            $ids = array_column($arr,'id');
            DB::table('base_role_menu')->whereIn('menu_id',$ids)->delete();
        }

        $admin_dict = DB::table('base_dict')->where('module_id',$this->module_id);
        $arr = $admin_dict->get()->toArray();
        if($arr){
            $admin_dict->delete();
            $ids = array_column($arr,'id');
            DB::table('base_dict_value')->whereIn('dict_id',$ids)->delete();
        }
        DB::table('base_config')->where('module_id',$this->module_id)->delete();
    }
}
