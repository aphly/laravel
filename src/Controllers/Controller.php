<?php

namespace Aphly\Laravel\Controllers;

use Illuminate\Support\Facades\Request;

class Controller extends \App\Http\Controllers\Controller
{
    function makeView($template,$data){
        if(Request::wantsJson()){
            return response()->json($data);
        }
        return view($template, $data);
    }
}
