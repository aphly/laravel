<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Aphly\Laravel\Models\Model;

class Banned extends Model
{
    use HasFactory;
    protected $table = 'admin_banned';
    //public $timestamps = false;
    protected $fillable = [
        'ip','uuid','status'
    ];

    public function getByCache()
    {
        return Cache::rememberForever('banned', function (){
            return self::where('status',1)->get()->toArray();
        });
    }

    public function isExist($ip)
    {
        $data = $this->getByCache();
        if($data){
           $ips = array_column($data,'ip');
           if(in_array($ip,$ips)){
               return true;
           }
        }
        return false;
    }
}
