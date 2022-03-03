<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Aphly\Laravel\Models\User;
use Aphly\Laravel\Models\UserAuth;
use Aphly\Laravel\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{

    public function layout()
    {
        $res['title']='我的';
        $res['user'] = Auth::guard('user')->user();
        return $this->makeView('laravel::common.layout',['res'=>$res]);
    }

    public function index()
    {
        $res['title']='我的';
        return $this->makeView('laravel::index.index',['res'=>$res]);
    }

    public function login(loginRequest $request)
    {
        if($request->isMethod('post')) {
            $credentials = $request->only('username', 'password');
            $credentials['status']=1;
            if (Auth::guard('user')->attempt($credentials)) {
                throw new ApiException(['code'=>0,'msg'=>'登录成功','data'=>['redirect'=>'/index','user'=>Auth::guard('user')->user()->toArray()]]);
            }
        }else{
            $res=['title'=>'后台登录'];
            return $this->makeView('laravel::index.login',['res'=>$res]);
        }
    }

    public function register(loginRequest $request)
    {
        if(config('laravel.identity_type')=='email'){
            if($request->isMethod('post')) {
                $post = $request->all();
                $post['identity_type'] = 'email';
                $post['uuid'] = Helper::uuid();
                $post['credential'] = Hash::make($post['credential']);
                $user = UserAuth::create($post);
                if($user->id){
                    Auth::guard('user')->login($user);
                    throw new ApiException(['code'=>0,'msg'=>'添加成功','data'=>['redirect'=>'/','user'=>Auth::guard('user')->user()->toArray()]]);
                }else{
                    throw new ApiException(['code'=>1,'msg'=>'添加失败']);
                }
            }else{
                $res=['title'=>'后台登录'];
                return $this->makeView('laravel::index.register',['res'=>$res]);
            }
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        throw new ApiException(['code'=>0,'msg'=>'成功退出','data'=>['redirect'=>'/login']]);
    }


}
