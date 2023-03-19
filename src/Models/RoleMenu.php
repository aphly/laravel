<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Aphly\Laravel\Models\Model;

class RoleMenu extends Model
{
    use HasFactory;
    protected $table = 'admin_role_menu';
    public $timestamps = false;
    protected $fillable = [
        'menu_id',
        'role_id',
    ];

    public function menu()
    {
        return $this->hasOne(Menu::class, 'id', 'menu_id');
    }



}
