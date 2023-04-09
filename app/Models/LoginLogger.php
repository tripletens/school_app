<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLogger extends Model
{
    use HasFactory;

    protected $table = 'login_logger';
    protected $fillable = [
        'uid',
        'browser',
        'ip_address',
        'location_data',
        'login_date',
    ];
}
