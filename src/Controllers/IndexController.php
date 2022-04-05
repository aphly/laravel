<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Aphly\Laravel\Models\User;
use Aphly\Laravel\Models\UserAuth;
use Aphly\Laravel\Requests\LoginRequest;
use Aphly\Laravel\Requests\RegisterRequest;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class IndexController extends Controller
{

    public function index(Request $request)
    {

        $res['title']='我的';
        $res['user'] = session('user');
        return $this->makeView('laravel::index.index',['res'=>$res]);
    }

    public function autoLogin(Request $request)
    {
        try {
            $decrypted = Crypt::decryptString($request->token);
            $user = User::where('token',$decrypted)->first();
            if($user){
                Auth::guard('user')->login($user);
                return redirect('/index');
            }
        } catch (DecryptException $e) {
            throw new ApiException(['code'=>1,'msg'=>'Token错误','data'=>['redirect'=>'/index']]);
        }
    }

    public function login(loginRequest $request)
    {
        if($request->isMethod('post')) {
            $arr['identifier'] = $request->input('identifier');
            $arr['identity_type'] = config('laravel.identity_type');
            $userAuth = UserAuth::where($arr)->first();
            if($userAuth){
                $key = 'user_'.$request->ip();
                if($this->limiter($key,5)){
                    if(Hash::check($request->input('credential',''),$userAuth->credential)){
                        $user = User::find($userAuth->uuid);
                        if($user->status==1){
                            Auth::guard('user')->login($user);
                            $userAuth->last_login = time();
                            $userAuth->last_ip = $request->ip();
                            $userAuth->save();
                            $user->token = Str::random(64);
                            $user->token_expire = time()+120*60;
                            $user->save();
                            $user_arr = $user->toArray();
                            session(['user'=>$user_arr]);
                            $redirect = Cookie::get('refer');
                            $redirect = $redirect??'/index';
                            throw new ApiException(['code'=>0,'msg'=>'登录成功','data'=>['redirect'=>$redirect,'user'=>$user_arr]]);
                        }else{
                            throw new ApiException(['code'=>3,'msg'=>'账号被冻结','data'=>['redirect'=>'/index']]);
                        }
                    }else{
                        $this->limiterIncrement($key,15*60);
                    }
                }else{
                    throw new ApiException(['code'=>2,'msg'=>'错误次数太多，被锁定15分钟','data'=>['redirect'=>'/index']]);
                }
            }
            throw new ApiException(['code'=>1,'msg'=>'邮箱或密码错误','data'=>['redirect'=>'/index']]);
        }else{
            $res=['title'=>'后台登录'];
            return $this->makeView('laravel::index.login',['res'=>$res]);
        }
    }

    public function register(RegisterRequest $request)
    {
        if($request->isMethod('post')) {
            $post = $request->all();
            $post['identity_type'] = config('laravel.identity_type');
            $post['uuid'] = Helper::uuid();
            $post['credential'] = Hash::make($post['credential']);
            $post['last_login'] = time();
            $post['last_ip'] = $request->ip();
            $userAuth = UserAuth::create($post);
            if($userAuth->id){
                $arr['nickname'] = str::random(8);
                $arr['token'] = $arr['uuid'] = $userAuth->uuid;
                $arr['token_expire'] = time();
                $arr['role_id'] = User::SET_ROLE_ID;
                $user = User::create($arr);
                Auth::guard('user')->login($user);
                $user_arr = $user->toArray();
                session('user', $user_arr);
                $redirect = Cookie::get('refer');
                $redirect = $redirect??'/index';
                throw new ApiException(['code'=>0,'msg'=>'添加成功','data'=>['redirect'=>$redirect,'user'=>$user_arr]]);
            }else{
                throw new ApiException(['code'=>1,'msg'=>'添加失败']);
            }
        }else{
            $res=['title'=>'后台登录'];
            return $this->makeView('laravel::index.register',['res'=>$res]);
        }
    }

    public function logout(Request $request)
    {
        session()->forget('user');
        Auth::guard('user')->logout();
        throw new ApiException(['code'=>0,'msg'=>'成功退出','data'=>['redirect'=>'/login']]);
    }


}
