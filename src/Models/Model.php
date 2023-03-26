<?php

namespace Aphly\Laravel\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as ModelBase;

class Model extends ModelBase
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


//    public function players(){
//        $instance = new Player();
//        $instance->setYear($this->year);
//
//        $foreignKey = $instance->getTable.'.'.$this->getForeignKey();
//        $localKey = $this->getKeyName();
//
//        return new HasMany($instance->newQuery(), $this, $foreignKey, $localKey);
//    }


    public static function getEloquentSqlWithBindings($query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }
}
