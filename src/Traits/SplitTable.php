<?php

namespace Aphly\Laravel\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait SplitTable
{
    public $type = 'month';

    public $originTable;

    protected $suffix = null;

    public function init()
    {
        $this->originTable = $this->table;
        if($this->type=='month'){
            $this->table = $this->originTable . '_'.date('Y_m').'_1';
        }else{
            $this->table = $this->originTable . '_'.date('Y_m_d');
        }
        $this->createTable($this->table);
    }

    protected function createTable($table)
    {
        $hasTable = Cache::get('has_' . $table);
        if(!$hasTable){
            if (!Schema::hasTable($table)) {
                DB::update("create table {$table} like {$this->originTable}");
            }
            Cache::set('has_' . $table, 1);
        }
    }

    public function makeUnionQuery($startTime,$endTime = null)
    {
        if($this->type=='month'){
            $queryList = static::getSubTablesByMonth($startTime,$endTime);
        }else{
            $queryList = static::getSubTablesByDay($startTime,$endTime);
        }
        $queries = collect();
        foreach ($queryList as $suffix) {
            $tempTable = $this->originTable .'_'. $suffix;
            $this->createTable($tempTable);
            $queries->push($tempTable);
        }
        $self = new self;
        $unionTableSql = implode('{SPLIT_TABLE_FLAG}', $queries->toArray());
        return $self->setTable(DB::raw("{SPLIT_TABLE}$unionTableSql{SPLIT_TABLE}"));
    }

    public static function getSubTablesByMonth($startTime, $endTime = null)
    {
        $time = time();
        $endTime = empty($endTime) ? $time : $endTime;
        if ($endTime instanceof \Illuminate\Support\Carbon) {
            $endTime = $endTime->timestamp;
        }
        $now = strtotime(date("Y-m-1", $endTime));
        $indexTime = empty($startTime) ? $time : $startTime;
        if ($indexTime instanceof \Illuminate\Support\Carbon) {
            $indexTime = $indexTime->timestamp;
        }
        $indexTime = strtotime(date("Y-m-1", $indexTime));
        $queryList = [];
        while ($indexTime <= $now) {
            $queryList[] = date('Y_m', $indexTime) . "_1";
            $indexTime = strtotime("+1 month", $indexTime);
        }
        return array_unique($queryList);
    }

    public static function getSubTablesByDay($startTime, $endTime = null)
    {
        $time = time();
        $endTime = empty($endTime) ? $time : $endTime;
        if ($endTime instanceof \Illuminate\Support\Carbon) {
            $endTime = $endTime->timestamp;
        }
        $indexTime = empty($startTime) ? $time : $startTime;
        if ($indexTime instanceof \Illuminate\Support\Carbon) {
            $indexTime = $indexTime->timestamp;
        }
        $queryList = [];
        while ($indexTime <= $endTime) {
            $queryList[] = date('Y_m_d', $indexTime);
            $indexTime = strtotime("+1 day", $indexTime);
        }
        return array_unique($queryList);
    }


}
