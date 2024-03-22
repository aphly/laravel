<?php

namespace Aphly\Laravel\Controllers\Front;

use Aphly\Laravel\Models\Comm;
use Aphly\Laravel\Models\Config;
use Aphly\Laravel\Models\Dict;
use Aphly\Laravel\Models\UploadFile;
use Aphly\Laravel\Models\Links;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Controller extends \Aphly\Laravel\Controllers\Controller
{
    public $user = null;

    public $config = null;

    static public $_G = ['comm_module'=>[]];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $auth = Auth::guard('user');
            $this->config = (new Config)->getByType();
            View::share("config",$this->config);
            if($auth->check()){
                $this->user = $auth->user();
                $this->user->avatar_src = UploadFile::getPath($this->user->avatar,$this->user->remote);
                View::share("user",$this->user);
            }else{
                View::share("user",[]);
            }
            View::share("dict",(new Dict)->getByKey());
            View::share("links",(new Links)->menu(config('base.link_id')));
//            $paginationTemplate = $this->existView(config('base.view_namespace_front').'::common.pagination');
//            if($paginationTemplate){
//                Paginator::defaultView($paginationTemplate);
//            }
            self::$_G['comm_module'] = (new Comm)->moduleClass();
            View::share("comm_module",self::$_G['comm_module']);
            foreach (self::$_G['comm_module'] as $val) {
                if ($val!='Aphly\LaravelAdmin' && class_exists($val.'\Controllers\Front\Controller')) {
                    $object = new ($val.'\Controllers\Front\Controller');
                    if (method_exists($object, 'afterController')) {
                        $object->afterController($this);
                    }
                }
            }
            return $next($request);
        });
    }

}
