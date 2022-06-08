<?php

namespace Aphly\Laravel\Logs;

use Illuminate\Support\Facades\Log;

class Debug
{
    static function write(string $channel='',string $msg='',array $arr=[]){
        if(!$msg){
            return ;
        }
        if($channel){
            Log::channel($channel)->debug($msg,$arr);
        }else{
            Log::debug($msg,$arr);
        }
    }
}
