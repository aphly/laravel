<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Level extends Model
{
    use HasFactory;
    protected $table = 'admin_level';
    public $timestamps = false;

    protected $fillable = [
        'name','pid','sort','status','type'
    ];

    public function findAll() {
        return Cache::rememberForever('level', function (){
            $level = self::where('status', 1)->orderBy('sort', 'desc')->get()->toArray();
            return Helper::getTree($level, true);
        });
    }


}
