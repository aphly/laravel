<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notice extends Model
{
    use HasFactory;
    protected $table = 'admin_notice';
    //public $timestamps = false;
    protected $fillable = [
        'title','content','viewed','status','uuid','level_id'
    ];

}
