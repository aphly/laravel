<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Traits\Tree;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Api extends Model
{
    use HasFactory,Tree;
    protected $table = 'admin_api';
    public $timestamps = false;

    protected $fillable = [
        'name','route','pid','type','status',
        'sort','level_id','module_id','uuid'
    ];


}
