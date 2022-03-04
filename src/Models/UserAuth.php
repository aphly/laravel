<?php

namespace Aphly\Laravel\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuth extends Model
{
    use HasFactory;
    protected $table = 'user_auth';
    protected $fillable = [
        'uuid','identity_type','identifier','credential',
    ];

    function check(){

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
