<?php

namespace Aphly\Laravel\Controllers\Front;


use Aphly\Laravel\Controllers\Controller;
use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Func;
use Aphly\Laravel\Libs\Seccode;
use Illuminate\Http\Request;
use function response;

class SeccodeController extends Controller
{

    public function index()
    {
        $seccode = Func::randStr(4,true);
        session(['seccode'=> $seccode]);
        $code = new Seccode();
        $code->code = $seccode;
        $content = $code->display();
        if($code->animator){
            return response($content,200,['Content-Type' => 'image/gif']);
        }else{
            return response($content,200,['Content-Type' => 'image/png']);
        }
    }

    public function check(Request $request)
    {
        if((new Seccode())->check($request->code)){
            throw new ApiException(['code'=>0,'msg'=>'success']);
        }
        throw new ApiException(['code'=>1,'msg'=>'fail']);
    }

}
