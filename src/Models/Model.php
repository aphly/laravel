<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Model as ModelBase;

class Model extends ModelBase
{
    public function fromDateTime($value){
        return strtotime(parent::fromDateTime($value));
    }

    public function findAllIds($ids) {
        return self::whereIn($this->primaryKey, $ids)->get()->keyBy($this->primaryKey)->toArray();
    }

}
