<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Config extends Model
{
    use HasFactory;
    protected $table = 'admin_config';
    public $timestamps = false;
    protected $fillable = [
        'type','key','value','level_id','name','module_id'
    ];

    function saveInput($request){
        $input = $request->all();
        $input['type'] = trim($input['type']);
        $input['key'] = trim($input['key']);
        self::updateOrCreate(['id'=>$request->query('id',0)],$input);
        Cache::forget('admin_config');
    }

    function getByType($type=''){
        $all = Cache::rememberForever('admin_config', function () {
            $arr = self::get()->toArray();
            $res = [];
            foreach ($arr as $val){
                $res[$val['type']][$val['key']] = $val['value'];
            }
            return $res;
        });
        if($type){
            return $all[$type]??[];
        }else{
            return $all;
        }
    }
}
