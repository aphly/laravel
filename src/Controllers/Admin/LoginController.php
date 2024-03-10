<?php

namespace Aphly\Laravel\Controllers\Admin;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Seccode;
use Aphly\Laravel\Models\FailedLogin;
use Aphly\Laravel\Models\Manager;
use Aphly\Laravel\Models\ManagerRole;
use Aphly\Laravel\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index(loginRequest $request)
    {
        if($request->isMethod('post')) {
            if (config('base.seccode_admin_login')==1) {
                if (!((new Seccode())->check($request->input('code')))) {
                    throw new ApiException(['code' => 11000, 'msg' => 'Incorrect Code', 'data' => ['code' => ['Incorrect Code']]]);
                }
            }
            $failedLogin =  new FailedLogin;
            $failedLogin->loginCheck($request);
            $credentials = $request->only('username', 'password');
            //$credentials['status']=1;
            $auth = Auth::guard('manager');
            if ($auth->attempt($credentials)) {
                $manager = $auth->user();
                $manager->last_ip = $request->ip();
                $manager->last_time = time();
                $manager->user_agent = $request->header('user-agent');
                $manager->accept_language = $request->header('accept-language');
                $manager->save();
                throw new ApiException(['code'=>0,'msg'=>'登录成功','data'=>['redirect'=>route('adminRole'),'manager'=>$manager->toArray()]]);
            }else{
                $failedLogin->updateFailed($request);
            }
        }else{
            $res['title'] = '';
            return $this->makeView('laravel::admin.login.index',['res'=>$res]);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('manager')->logout();
        session()->forget('role_id');
        throw new ApiException(['code'=>0,'msg'=>'成功退出','data'=>['redirect'=>route('adminLogin')]]);
    }

    public function notActive()
    {
        $res['title'] = 'Not Active';
        return $this->makeView('laravel::admin.login.not_active',['res'=>$res]);
    }

    public function blocked()
    {
        $res['title'] = 'Blocked';
        return $this->makeView('laravel::admin.login.blocked',['res'=>$res]);
    }

    public function role()
    {
        $res['title'] = 'role';
        $res['roleData'] = ManagerRole::leftJoin('admin_role','admin_role.id','=','admin_manager_role.role_id')
            ->where('admin_role.status',1)
            ->where('admin_manager_role.uuid',Manager::_uuid())->get();
        if($res['roleData']->count()==1){
            $activeRoleId = $res['roleData'][0]->role_id;
            session(['role_id' => $activeRoleId]);
            throw new ApiException(['code'=>0,'msg'=>'role success','data'=>['redirect'=>route('adminIndex')]]);
        }
        return $this->makeView('laravel::admin.login.role',['res'=>$res]);
    }

    public function chooseRole(Request $request)
    {
        $res['role'] = ManagerRole::leftJoin('admin_role','admin_role.id','=','admin_manager_role.role_id')
            ->where('admin_role.status',1)
            ->where(['admin_manager_role.uuid'=>Manager::_uuid(),'admin_manager_role.role_id'=>$request->query('role_id')])
            ->firstOr404();
        session(['role_id' => $res['role']->role_id]);
        throw new ApiException(['code'=>0,'msg'=>'role success','data'=>['redirect'=>route('adminIndex')]]);
    }
}
