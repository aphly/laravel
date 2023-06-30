<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MsgDetail extends Model
{
    use HasFactory;
    protected $table = 'admin_msg_detail';
    public $timestamps = false;
    protected $fillable = [
        'title','content'
    ];

}
