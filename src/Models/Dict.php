<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Aphly\Laravel\Models\Model;

class Dict extends Model
{
    use HasFactory;
    protected $table = 'admin_dict';
    public $timestamps = false;
    protected $fillable = [
        'name','sort','key','module_id'
    ];

    public function getByKey()
    {
        return Cache::rememberForever('dict', function (){
            //$arr = self::with('dictValue')->get()->toArray();
            $arr = self::leftJoin('admin_dict_value','admin_dict_value.dict_id','=','admin_dict.id')->get()->toArray();
            $res = [];
            foreach ($arr as $val){
                $res[$val['key']][$val['value']] = $val['name'];
            }
            return $res;
        });
    }

    public function dictValue()
    {
        return $this->hasMany(DictValue::class);
    }
}
