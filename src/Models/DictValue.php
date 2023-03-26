<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class DictValue extends Model
{
    use HasFactory;
    protected $table = 'admin_dict_value';
    public $timestamps = false;
    protected $fillable = [
        'name','dict_id','value','sort'
    ];




}
