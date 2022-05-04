<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Libs\UploadFile;
use Illuminate\Http\Request;

class UploadController extends Controller
{

    public function index(Request $request)
    {
        $img_src = UploadFile::imgs($request->file('file'), 'public/test',2);
        dd($img_src);
    }

    public function form()
    {
        $res['title'] = '';
        return $this->makeView('laravel::test.form',['res'=>$res]);
    }

}
