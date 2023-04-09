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


}
