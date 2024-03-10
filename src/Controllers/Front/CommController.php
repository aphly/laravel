<?php

namespace Aphly\Laravel\Controllers\Front;

use Aphly\Laravel\Controllers\Controller;
use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Models\Comm;
use Aphly\Laravel\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommController extends Controller
{
    public function checkAuth_key($request)
    {
        $comm_id = $request->query('comm_id',0);
        $info = Comm::where('id',$comm_id)->firstOrError();
        $sign = $request->query('sign','');
        $local_sign = md5(md5($info->id.$info->host.$info->auth_key));
        if($comm_id && $sign && $sign==$local_sign){
            return $info;
        }else{
            throw new ApiException(['code'=>1,'msg'=>'auth fail']);
        }
    }

    public function module(Request $request)
    {
        $info = $this->checkAuth_key($request);
        $res['list'] = Module::where('comm_id', $info->id)
            ->orderBy('id', 'desc')
            ->get();
        $aphly = $this->getAphly();
        $module = Module::get()->pluck('classname')->all();
        $res['unimport'] = [];
        foreach ($aphly as $val){
            if(!in_array($val,$module)){
                $res['unimport'][] = $val;
            }
        }
        $res['getAphly'] = $aphly;
        throw new ApiException(['code'=>0,'msg'=>'success','data'=>$res]);
    }

    function getAphly(){
        $aphly = [];
        $providers = config('app.providers');
        foreach($providers as $provider){
            if(preg_match('/^Aphly\\\\/',$provider) && !preg_match('/^Aphly\\\\Laravel\\\\/',$provider)
                && !preg_match('/^Aphly\\\\LaravelAdmin\\\\/',$provider)){
                $r = strrchr($provider, '\\');
                $aphly[] = str_replace($r,'',$provider);
            }
        }
        return $aphly;
    }

    public function moduleImport(Request $request)
    {
        $commInfo = $this->checkAuth_key($request);
        $input = $request->all();
        if(!empty($input['class'])){
            Module::updateOrCreate(['classname'=>$input['class']],[
                'name'=>str_replace('Aphly\Laravel','',$input['class']),
                'classname'=>$input['class'],
                'status'=>0,
                'comm_id'=>$commInfo->id,
            ]);
            throw new ApiException(['code'=>0,'msg'=>'success']);
        }
        throw new ApiException(['code'=>1,'msg'=>'fail']);
    }

    public function moduleInstall(Request $request)
    {
        $commInfo = $this->checkAuth_key($request);
        $id = $request->query('id',0);
        $info = Module::where('id',$id)->firstOrError();
        $path = require base_path('vendor/composer').'/autoload_psr4.php';
        $paths = $path[$info->classname.'\\'][0].'/migrations';
        if(is_dir($paths)){
            $migrator = app('migrator');
            $migrator->run($paths);
        }
        try{
            $module = new ($info->classname.'\\Models\\Module');
            $module->remoteInstall($info->id);
        }catch(ApiException $e){
            throw $e;
        }
        $info->status=1;
        $info->save();
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }

    public function moduleUninstall(Request $request)
    {
        $commInfo = $this->checkAuth_key($request);
        $id = $request->query('id',0);
        $info = Module::where('id',$id)->firstOrError();
        $path = require base_path('vendor/composer').'/autoload_psr4.php';
        $module_id = $info->id;
        $paths = $path[$info->classname.'\\'][0].'/migrations';
        if(is_dir($paths)) {
            $migrator = app('migrator');
            $files = $migrator->getMigrationFiles($paths);
            $keys = array_keys($files);
            $batchInfo = DB::table('migrations')->whereIn('migration',$keys)->first();
            if(!empty($batchInfo)){
                $options['batch']= $batchInfo->batch;
                $migrator->rollback($paths,$options);
            }
        }

        DB::table('admin_level')->where('module_id',$module_id)->delete();
        DB::table('admin_role')->where('module_id',$module_id)->delete();

        $admin_menu = DB::table('admin_menu')->where('module_id',$module_id);
        $arr = $admin_menu->get()->toArray();
        if($arr){
            $admin_menu->delete();
            $ids = array_column($arr,'id');
            DB::table('admin_role_menu')->whereIn('menu_id',$ids)->delete();
        }

        $admin_dict = DB::table('admin_dict')->where('module_id',$module_id);
        $arr = $admin_dict->get()->toArray();
        if($arr){
            $admin_dict->delete();
            $ids = array_column($arr,'id');
            DB::table('admin_dict_value')->whereIn('dict_id',$ids)->delete();
        }
        DB::table('admin_config')->where('module_id',$module_id)->delete();
        try{
            $module = new ($info->classname.'\\Models\\Module');
            $module->remoteUninstall($info->id);
        }catch(ApiException $e){
            throw $e;
        }
        $info->status=0;
        $info->save();
        throw new ApiException(['code'=>0,'msg'=>'success']);
    }
}
