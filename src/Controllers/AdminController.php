<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Models\Dict;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\Role;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{

    public $manager = null;

    public $roleLevelIds = [];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->manager = Manager::user();
            $this->roleLevelIds = (new Role)->hasLevelIds(session('role_id'));
            View::share("manager",$this->manager);
            View::share("action",explode('@',$request->route()->action['controller']));
            View::share("dict",(new Dict)->getByKey());
            date_default_timezone_set('Asia/Shanghai');
            return $next($request);
        });
    }


}
