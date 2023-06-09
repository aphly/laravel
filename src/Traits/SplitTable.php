<?php

namespace Aphly\Laravel\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait SplitTable
{
    static public $type = 'month';

    static public $table_min_date = '2023-01-01';

    static public $originTable;

    static public function init()
    {
        $self = new self;
        self::$originTable = $self->table;
        if(self::$type=='month'){
            $self->table = self::$originTable . '_'.date('Y_m').'_1';
        }else{
            $self->table = self::$originTable . '_'.date('Y_m_d');
        }
        self::createTable($self->table);
        return $self;
    }

    static public function createTable($table)
    {
        $hasTable = Cache::get('has_' . $table);
        if(!$hasTable){
            if (!Schema::hasTable($table)) {
                DB::update('create table '.$table.' like '.self::$originTable);
            }
            Cache::set('has_' . $table, 1);
        }
    }

    static public function makeUnionQuery($startTime,$endTime = null)
    {
        $self = self::init();
        $queryList = static::getSubTable($startTime,$endTime);
        if(!$queryList){
            return $self;
        }
        $queries = collect();
        foreach ($queryList as $suffix) {
            $tempTable = self::$originTable .'_'. $suffix;
            self::createTable($tempTable);
            $queries->push($tempTable);
        }
        $unionTableSql = implode('{SPLIT_TABLE_FLAG}', $queries->toArray());
        return $self->setTable(DB::raw("{SPLIT_TABLE}$unionTableSql{SPLIT_TABLE}"));
    }

    public static function getSubTable($startTime, $endTime = null)
    {
        $time = time();
        if($startTime>$time){return [];}
        $endTime = empty($endTime) ? $time : $endTime;
        if ($endTime instanceof \Illuminate\Support\Carbon) {
            $endTime = $endTime->timestamp;
        }
        $indexTime = empty($startTime) ? $time : $startTime;
        if ($indexTime instanceof \Illuminate\Support\Carbon) {
            $indexTime = $indexTime->timestamp;
        }
        if(self::$type=='month'){
            $now = strtotime(date("Y-m-1", $endTime));
            $indexTime = strtotime(date("Y-m-1", $indexTime));
        }else{
            $now = $endTime;
        }
        $min_date = strtotime(self::$table_min_date);
        if($indexTime<$min_date){
            $indexTime = $min_date;
        }
        $queryList = [];
        while ($indexTime <= $now) {
            $queryList[] = date('Y_m', $indexTime) . "_1";
            if(self::$type=='month') {
                $indexTime = strtotime("+1 month", $indexTime);
            }else{
                $indexTime = strtotime("+1 day", $indexTime);
            }
        }
        return array_unique($queryList);
    }


}
