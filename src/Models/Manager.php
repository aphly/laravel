<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Manager extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'admin_manager';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    static public $_uuid = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function fromDateTime($value){
        return strtotime(parent::fromDateTime($value));
    }

    protected $fillable = [
        'uuid','username','nickname','email','phone','password','token',
        'token_expire','avatar','status','gender','status','level_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       //'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsToMany(Role::class,'admin_manager_role','uuid','role_id','uuid');
    }

    static function _uuid(){
        if(!self::$_uuid){
            $auth = Auth::guard('manager');
            if($auth->check()){
                return self::$_uuid = $auth->user()->uuid;
            }else{
                return 0;
            }
        }else{
            return self::$_uuid;
        }
    }

    protected static function boot()
    {
        parent::boot();
//        static::created(function (User $user) {
//            UserUni::create(['user_id'=>$user->id]);
//            UserInfo::create(['user_id'=>$user->id]);
//        });
//
//        static::deleted(function (User $user) {
//            UserUni::destroy($user->id);
//            UserInfo::destroy($user->id);
//            self::delAvatar($user->avatar);
//        });
    }

    public function logout() {
        $this->token = null;
        $this->token_expire = 0;
        return $this->save();
    }

    public function register($post) {
        $this->username = Str::lower(Str::random(10));
        $this->phone = $post['phone'];
        $this->password = Hash::make($post['password']);
        $this->api_token = Str::random(64);
        $this->api_token_expire = time()+120*60;
        $this->save();
        return $this;
    }

    public function updatecredit($user_id,$credit)
    {
        DB::transaction(function () {
            DB::update('update users set votes = 1');
            DB::delete('delete from posts');
        },2);
    }

}
