<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Module extends Model
{
    use HasFactory;
    protected $table = 'admin_module';
    public $timestamps = false;
    protected $fillable = [
        'name','sort','key','status','classname'
    ];

    public function getByCache()
    {
        return Cache::rememberForever('module', function (){
            $return = [];
            $arr = self::where('status',1)->get()->toArray();
            foreach ($arr as $val){
                $return[$val['id']] = $val['name'];
            }
            return $return;
        });
    }

    public function install($module_id){
        $paths = dirname($this->dir).'/migrations';
        if(is_dir($paths)){
            $migrator = app('migrator');
            $migrator->run($paths);
        }
    }

    public function uninstall($module_id){
        $paths =  dirname($this->dir).'/migrations';
        if(is_dir($paths)) {
            $migrator = app('migrator');
            $migrator->rollback($paths);
        }

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
    }
}
