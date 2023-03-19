<?php

namespace Aphly\Laravel\Controllers;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Aphly\Laravel\Models\Manager;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    protected $providers = [
        'wechat'
    ];

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $auth = Auth::guard('manager');
            if ($auth->check()) {
                return redirect(url(''));
            }else{
                return $next($request);
            }
        });
        parent::__construct();
    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers);
    }

    public function redirectToProvider($driver)
    {
        if(!$this->isProviderAllowed($driver)) {
            throw new ApiException(['code'=>1,'msg'=>"{$driver} is not currently supported"]);
        }
        try {
            return Socialite::driver($driver)->redirect();
        } catch (\Exception $e) {
            throw new ApiException(['code'=>2,'msg'=>$e->getMessage()]);
        }
    }

    public function handleProviderCallback($driver)
    {
        try {
            //$oauthUser = Socialite::driver($driver)->stateless()->user();
            $oauthUser = Socialite::driver($driver)->user();
        } catch (\Exception $e) {
            throw new ApiException(['code'=>1,'msg'=>$e->getMessage()]);
        }
        dd($oauthUser);
        return $this->loginOrCreateAccount($oauthUser, $driver);
    }

    protected function loginOrCreateAccount($oauthUser, $driver)
    {
        $arr['id'] = $oauthUser->id;
        $arr['id_type'] = $driver;
        $note = $driver.' - '.$oauthUser->email;
        $request = request();
        $manager = Manager::where($arr)->first();
        if(!empty($manager)){
            $manager->update(['last_time'=>time(),'last_ip'=>$request->ip(),'user_agent'=>$request->header('user-agent'),'accept_language'=>$request->header('accept-language')]);
        }else{
            $arr['uuid'] = Helper::uuid();
            $arr['password'] = $oauthUser->token;
            $arr['last_time'] = time();
            $arr['last_ip'] = $request->ip();
            $arr['note'] = $note;
            $manager = Manager::create($arr);
        }
        Auth::guard('manage')->login($manager);
        return redirect()->route('adminLogin');
    }
}
