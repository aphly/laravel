<?php

namespace Aphly\Laravel\Libs;

use Illuminate\Support\Facades\DB;

class Sql
{
    public function put_in($path){
        DB::unprepared(file_get_contents($path));
    }
}
