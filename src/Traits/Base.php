<?php

namespace Aphly\Laravel\Traits;

use Aphly\Laravel\Models\Level;
use Aphly\Laravel\Models\Manager;
use DateTimeInterface;

trait Base
{
    protected $year = null;

    public static function year($year){
        $instance = new static;
        $instance->setYear($year);
        return $instance->newQuery();
    }

    public function setYear($year){
        $this->year = $year;
        if($year != null){
            $this->table = $this->getTable().'_'.$year;
        }
    }

    public function fromDateTime($value){
        return strtotime(parent::fromDateTime($value));
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }

    public function findAllIds($ids) {
        return self::whereIn($this->primaryKey, $ids)->get()->keyBy($this->primaryKey)->toArray();
    }

    public static function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }

    public function manager()
    {
        return $this->hasOne(Manager::class,'uuid','uuid');
    }

    public function level()
    {
        return $this->hasOne(Level::class,'id','level_id');
    }

    public function scopeDataPerm($query,$uuid,$level_ids=[])
    {
        if($level_ids){
            return $query->whereIn('level_id', $level_ids)->with('manager')->with('level');
        }else{
            return $query->where('uuid', $uuid)->with('manager')->with('level');
        }
    }


}
