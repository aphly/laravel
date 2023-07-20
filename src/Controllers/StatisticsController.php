<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Aphly\Laravel\Models\Statistics;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function add(Request $request)
    {
        if($request->isMethod('post')) {
            $input = $request->all();
            $input['ipv4'] = $request->ip();
            $info = Statistics::where('ipv4',$input['ipv4'])->where('url',$input['url'])->first();
            if(!empty($info) && Helper::is_today($info->created_at->timestamp)){
                $info->increment('view');
            }else{
                Statistics::create($input);
            }
        }
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }
}
