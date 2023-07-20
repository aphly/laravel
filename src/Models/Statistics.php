<?php

namespace Aphly\Laravel\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Statistics extends Model
{
    use HasFactory;
    protected $table = 'admin_statistics';
    //public $timestamps = false;
    protected $fillable = [
        'ipv4','url','referrer','keyword','language','platform','userAgent','webdriver','ipv6','view'
    ];

}
