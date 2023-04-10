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
        'name','pid','sort','status','type','module_id'
    ];

    public function findAll() {
        return Cache::rememberForever('level', function (){
            $level = self::where('status', 1)->orderBy('sort', 'desc')->get()->toArray();
            return Helper::getTree($level, true);
        });
    }


}
