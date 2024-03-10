<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleApi extends Model
{
    use HasFactory;
    protected $table = 'admin_role_api';
    public $timestamps = false;
    protected $fillable = [
        'api_id',
        'role_id',
    ];


}
