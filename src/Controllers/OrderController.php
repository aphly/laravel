<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Jobs\OrderJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $res['title'] = '';
        $res['order'] = DB::table('test_order')
            ->leftJoin('test_express', 'test_order.order_id', '=', 'test_express.order_id')
            ->select('test_order.order_id','test_order.email','test_order.site','test_express.express_id','test_order.status')
            ->get()->toArray();
        return $this->makeView('laravel::order.index',['res'=>$res]);
    }

    public function express(Request $request)
    {
        $order = DB::table('test_order')
            ->leftJoin('test_express', 'test_order.order_id', '=', 'test_express.order_id')
            ->select('test_order.order_id','test_order.email','test_order.site','test_express.express_id','test_order.status')
            ->get()->toArray();
        foreach($order as $val){
            if($val->express_id){
                OrderJob::dispatch($val)->onQueue('low');
            }
        }
        return '邮件发送中...';
    }

    public function import_order(Request $request)
    {
        DB::table('test_order')->truncate();
        $max = 200000;
        $dpath = public_path('/express');
        $config = ['path' => $dpath];
        $excel = new \Vtiful\Kernel\Excel($config);
        $excel->openFile('order.xlsx')->openSheet();
        $i = 1;
        $insertData = array();
        while ($infos = $excel->nextRow()) {
            if ($i == 1) {
                $i++;
                continue;
            } else if ($i == $max) {
                break;
            } else {
                if ($infos[0]) {
                    $insertData[$i]['order_id'] = intval(trim($infos[0]));
                    $insertData[$i]['email'] = trim($infos[1]);
                    $insertData[$i]['site'] = $infos[2];
                    $insertData[$i]['status'] = 0;
                }
                $i++;
            }
        }
        DB::table('test_order')->insert($insertData);
        return 'order_ok';
    }
    public function import_express(Request $request)
    {
        DB::table('test_express')->truncate();
        $max = 200000;
        $dpath = public_path('/express');
        $config = ['path' => $dpath];
        $excel = new \Vtiful\Kernel\Excel($config);
        $excel->openFile('express.xlsx')->openSheet();
        $i = 1;
        $insertData = array();
        while ($infos = $excel->nextRow()) {
            if ($i == 1) {
                $i++;
                continue;
            } else if ($i == $max) {
                break;
            } else {
                if ($infos[0]) {
                    $insertData[$i]['order_id'] = intval(trim($infos[0]));
                    $insertData[$i]['express_id'] = $infos[1];
                }
                $i++;
            }
        }
        DB::table('test_express')->insert($insertData);
        return 'express_ok';
    }
}
