<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Model as ModelBase;

class Model extends ModelBase
{
    public function findAllIds($ids) {
        return self::whereIn($this->primaryKey, $ids)->get()->keyBy($this->primaryKey)->toArray();
    }
}
