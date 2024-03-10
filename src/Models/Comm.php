<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Traits\Tree;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comm extends Model
{
    use HasFactory,Tree;
    protected $table = 'admin_comm';
    public $timestamps = false;

    protected $fillable = [
        'name','host','status','auth_key','sort'
    ];

    public function module()
    {
        return $this->hasMany(Module::class,'comm_id','id');
    }

    public function moduleClass()
    {
        $comm = Comm::where('host',config('base.local_host'))->with(['module' => function ($query) {
            $query->where('status',1);
        }])->first();
        if(!empty($comm)){
            return $comm->module->pluck('classname')->toArray();
        }
        return [];
    }
}
