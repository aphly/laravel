<?php
namespace Aphly\Laravel\Libs;

use Illuminate\Support\Facades\Redis;

class Snowflake
{
    const EPOCH = 1640995200000;

    static $dataCenterId = 0;      // 机房id
    static $machineId = 0;      // 机器id

    static $redis = false;

    public static function id($dataCenterId = 0, $machineId = 0)
    {
        $maxbit = pow(2,17);
        self::$machineId = $machineId;
        self::$dataCenterId = $dataCenterId;
        $time = floor(microtime(true) * 1000);
        $time -= self::EPOCH;
        $timeStr = str_pad(decbin($time), 41, "0", STR_PAD_LEFT);
        $dataCenterId = str_pad(decbin(self::$dataCenterId), 3, "0", STR_PAD_LEFT);
        $machineId = str_pad(decbin(self::$machineId), 3, "0", STR_PAD_LEFT);
        if(self::$redis){
            $random = str_pad(decbin(self::redisId(self::$dataCenterId.self::$machineId,$maxbit)), 23, "0", STR_PAD_LEFT);
        }else{
            $random = str_pad(decbin(mt_rand(0, $maxbit)), 17, "0", STR_PAD_LEFT);
        }
        return bindec($timeStr . $dataCenterId . $machineId . $random);
    }

    public static function incrId($key='order')
    {
        $maxbit = pow(2,23);
        $time = floor(microtime(true) * 1000);
        $time -= self::EPOCH;
        $timeStr = str_pad(decbin($time), 41, "0", STR_PAD_LEFT);
        if(self::$redis){
            $random = str_pad(decbin(self::redisId($key,$maxbit)), 23, "0", STR_PAD_LEFT);
        }else{
            $random = str_pad(decbin(mt_rand(0, $maxbit)), 23, "0", STR_PAD_LEFT);
        }
        return bindec($timeStr . $random);
    }

    public static function redisId($key,$maxbit)
    {
        $key = 'incr:'.$key.':'.date("Y:m:d");
        $values = Redis::get($key);
        if($values){
            if($values>=$maxbit){
                $values = 1;
                Redis::set($key,$values);
            }else{
                $values = Redis::incr($key);
            }
        }else{
            $values = Redis::incr($key);
        }
        return $values;
    }
}

