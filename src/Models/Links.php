<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Links extends Model
{
    use HasFactory;
    protected $table = 'admin_links';
    public $timestamps = false;

    protected $fillable = [
        'name','icon','pid','sort','status','url','type'
    ];

    public function menu($id=0): array
    {
        $tree = Cache::rememberForever('links', function () {
            $tree = self::where('status', 1)->orderBy('sort', 'desc')->get()->toArray();
            return Helper::getTree($tree, true);
        });
        $links = $tree;
        if($id){
            Helper::getTreeByid($tree,$id,$links);
        }
        return $links;
    }


}
