<?php

namespace Aphly\Laravel\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model as ModelBase;

class Model extends ModelBase
{
    public function fromDateTime($value){
        return strtotime(parent::fromDateTime($value));
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }

    public function findAllIds($ids) {
        return self::whereIn($this->primaryKey, $ids)->get()->keyBy($this->primaryKey)->toArray();
    }

    public static function year($year){
        $instance = new static;
        $instance->setYear($year);
        return $instance->newQuery();
    }

    protected $year = null;

    public function setYear($year){
        $this->year = $year;
        if($year != null){
            $this->table = $this->getTable().'_'.$year;
        }
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

}
