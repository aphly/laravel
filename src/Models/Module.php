<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Aphly\Laravel\Models\Model;

class Module extends Model
{
    use HasFactory;
    protected $table = 'admin_module';
    public $timestamps = false;
    protected $fillable = [
        'name','sort','key','status','classname'
    ];

    public function getByCache()
    {
        return Cache::rememberForever('module', function (){
            $return = [];
            $arr = self::where('status',1)->get()->toArray();
            foreach ($arr as $val){
                $return[$val['id']] = $val['name'];
            }
            return $return;
        });
    }


}
