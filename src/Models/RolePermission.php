<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class RolePermission extends Model
{
    use HasFactory;
    protected $table = 'admin_role_permission';
    public $timestamps = false;
    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }


}
