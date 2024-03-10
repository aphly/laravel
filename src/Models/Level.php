<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Aphly\Laravel\Traits\Tree;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Level extends Model
{
    use HasFactory,Tree;
    protected $table = 'admin_level';
    public $timestamps = false;

    protected $fillable = [
        'name','pid','sort','status','type','module_id','uuid'
    ];

    public function findAll($cache=true) {
        if($cache){
            return Cache::rememberForever('level', function (){
                $level = self::where('status', 1)->orderBy('sort', 'desc')->get()->toArray();
                return Helper::getTree($level, true);
            });
        }else{
            $level = self::where('status', 1)->orderBy('sort', 'desc')->get()->toArray();
            return Helper::getTree($level, true);
        }
    }


}
