<?php

namespace Aphly\Laravel\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserAuth extends Model
{
    use HasFactory;
    protected $table = 'user_auth';
    protected $fillable = [
        'uuid','identity_type','identifier','credential',
    ];

    function changePassword($uuid,$password){
        $credential = Hash::make($password);
        $this->where(['identity_type'=>'username','uuid'=>$uuid])->update(['credential'=>$credential]);
        $this->where(['identity_type'=>'mobile','uuid'=>$uuid])->update(['credential'=>$credential]);
        $this->where(['identity_type'=>'email','uuid'=>$uuid])->update(['credential'=>$credential]);
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
