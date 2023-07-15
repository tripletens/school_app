<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmpSettings extends Model
{
    use HasFactory;

    protected $table = 'smtp_settings';

    protected $fillable = [
        'hostname',
        'protocol',
        'username',
        'password',
        'created_by',
        'status',
        'is_active',
    ];
}
