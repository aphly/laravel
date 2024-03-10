<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class UserAuth extends Model
{
    use HasFactory;
    protected $table = 'admin_user_auth';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = ['id_type','id'];
    protected $fillable = [
        'uuid','id_type','id','password','user_agent','accept_language','last_ip','verified','last_time'
    ];

    static public $id_type = ['username','mobile','email'];

    function changePassword($uuid,$password){
        $password = Hash::make($password);
        foreach (self::$id_type  as $val){
            $this->where(['id_type'=>$val,'uuid'=>$uuid])->update(['password'=>$password]);
        }
        return true;
    }

    function changeVerify($uuid,$verified){
        $this->where(['id_type'=>'email','uuid'=>$uuid])->update(['verified'=>$verified]);
        return true;
    }

//    protected static function boot()
//    {
//        parent::boot();
//        static::created(function (UserAuth $user) {
//            $post['uuid'] = $post['token'] = $user->uuid;
//            $post['token_expire'] = time();
//            User::create($post);
//        });
//
//    }
}
