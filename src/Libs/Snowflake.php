<?php
namespace Aphly\Laravel\Libs;

class Snowflake
{
    const EPOCH = 1640995200000;

    static $dataCenterId = 0;      // 机房id
    static $machineId = 0;      // 机器id
    const max12bit = 65535;

    public static function id($dataCenterId = 0, $machineId = 0)
    {
        self::$machineId = $machineId;
        self::$dataCenterId = $dataCenterId;
        $time = floor(microtime(true) * 1000);
        $time -= self::EPOCH;
        $timeStr = str_pad(decbin($time), 41, "0", STR_PAD_LEFT);
        $dataCenterId = str_pad(decbin(self::$dataCenterId), 3, "0", STR_PAD_LEFT);
        $machineId = str_pad(decbin(self::$machineId), 3, "0", STR_PAD_LEFT);
        $random = str_pad(decbin(mt_rand(0, self::max12bit)), 16, "0", STR_PAD_LEFT);
        //REDIS::incr($time.self::$dataCenterId.self::$machineId);
        $base = $timeStr . $dataCenterId . $machineId . $random;
        return bindec($base);
    }
}

