<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Msg extends Model
{
    use HasFactory;
    protected $table = 'admin_msg';
    //public $timestamps = false;
    protected $fillable = [
        'msg_detail_id','viewed','status','uuid','to_uuid'
    ];

    function user(){
        return $this->hasOne(Manager::class,'uuid','to_uuid');
    }

    function msgDetail(){
        return $this->hasOne(MsgDetail::class,'id','msg_detail_id');
    }
}
