<?php

namespace Aphly\Laravel\Controllers\Front;

use Aphly\Laravel\Controllers\Controller;


class NotfoundController extends Controller
{

    public function index()
    {
        $res['title'] = '404';
        return $this->makeView('laravel-front::common.notfound',['res'=>$res]);
    }


}
