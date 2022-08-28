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

}
