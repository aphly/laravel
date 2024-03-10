<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'admin_user';
    protected $primaryKey = 'uuid';
    public $incrementing = false;


	static public $uuid = 0;

    static public $id = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function fromDateTime($value){
        return strtotime(parent::fromDateTime($value));
    }

    protected $fillable = [
        'uuid','nickname','token','remote',
        'token_expire','avatar','status','gender','comm_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       //'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
//        static::created(function (User $user) {
//            UserCredit::create(['uuid'=>$user->uuid]);
//        });
//
//        static::deleted(function (User $user) {
//            UserCredit::destroy($user->uuid);
//            //self::delAvatar($user->avatar);
//        });
    }

    public function userAuth()
    {
        return $this->hasMany(UserAuth::class,'uuid');
    }

//    static public function groupId() {
//        $auth = Auth::guard('user');
//        if($auth->check()){
//            $user = $auth->user();
//            if($user->group_id>1){
//                if($user->group_expire<time()){
//                    $user->group_id = self::$group_id;
//                    $user->save();
//                }
//            }
//            return $user->group_id;
//        }else{
//            return 0;
//        }
//    }

    static function uuid(){
    	if(!self::$uuid){
			$auth = Auth::guard('user');
			if($auth->check()){
				return self::$uuid = $auth->user()->uuid;
			}else{
				return 0;
			}
		}else{
			return self::$uuid;
		}
    }

    static function initId($id_type='email'){
        if(!self::$id){
            $arr['id_type'] = $id_type;
            $arr['uuid'] = self::uuid();
            $userAuthModel = UserAuth::where($arr)->first();
            self::$id = $userAuthModel->id;
        }
        return self::$id;
    }

//    function group(){
//        return $this->hasOne(Group::class,'id','group_id');
//    }
//
//    function credit(){
//        return $this->hasOne(UserCredit::class,'uuid','uuid');
//    }
//

    public function afterRegister()
    {
        $class = (new Comm)->moduleClass();
        foreach ($class as $val) {
            if (class_exists($val.'\Models\AfterUser')) {
                $object = new ($val.'\Models\AfterUser');
                if (method_exists($object, 'afterRegister')) {
                    $object->afterRegister($this);
                }
            }
        }
    }


    public function afterLogin()
    {
        $class = (new Comm)->moduleClass();
        foreach ($class as $val) {
            if (class_exists($val.'\Models\AfterUser')) {
                $object = new ($val.'\Models\AfterUser');
                if (method_exists($object, 'afterLogin')) {
                    $object->afterLogin($this);
                }
            }
        }
    }

    public function generateToken(){
        $this->token = Str::random(64);
        $this->token_expire = time()+120*60;
        return $this->save();
    }

    public function redirect(){
        $redirect = request()->query('redirect',false);
        if($redirect){
            return urldecode($redirect);
        }
        return '/';
    }
}
