<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    use HasFactory;
    protected $table = 'dictionary';
    public $timestamps = false;
    protected $fillable = [
        'name','sort','pid','status','is_leaf','json','icon'
    ];


}
