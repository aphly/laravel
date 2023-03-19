<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class FailedLogin extends Model
{
    use HasFactory;
    protected $table = 'admin_failed_login';
    protected $primaryKey = 'id';
    //protected $keyType = 'string';
    //public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'ip','input','user_agent','accept_language'
    ];

    const LIMITTIMES=5;
    static $failTimes=0;

    function loginCheck($request) {
        $ip = $request->ip();
        $time = time();
        $count = self::where('ip',$ip)->whereBetween('created_at',[$time-900,$time])->count();
        self::$failTimes = $count;
        if($count>=self::LIMITTIMES) {
            throw new ApiException(['code'=>1,'msg'=>'密码错误超过'.self::LIMITTIMES.'次数，请15分钟后再试']);
        }
    }

    function updateFailed($request){
        $input = $request->only('username', 'password');
        self::create([
            'ip'=>$request->ip(),
            'input'=>json_encode($input),
            'user_agent' => $request->header('user-agent'),
            'accept_language' => $request->header('accept-language')
        ]);
        $hasTimes = self::LIMITTIMES-(self::$failTimes+1);
        $msg = $hasTimes>0?'密码错误，还有'.$hasTimes.'次尝试机会':'密码错误，请等待15分钟再试';
        throw new ApiException(['code'=>2,'msg'=>$msg]);
    }

}
