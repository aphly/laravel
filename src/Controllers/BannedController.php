<?php

namespace Aphly\Laravel\Controllers;

use Illuminate\Http\Request;

class BannedController extends Controller
{
    public function index(Request $request)
    {
        $res['title'] = 'Banned';
        return $this->makeView('laravel::common.banned',['res'=>$res]);
    }
}
