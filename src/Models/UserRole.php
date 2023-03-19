<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $table = 'admin_user_role';
    public $timestamps = false;
    protected $fillable = [
        'uuid',
        'role_id',
    ];
}
