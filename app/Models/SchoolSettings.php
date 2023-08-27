<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSettings extends Model
{
    use HasFactory;

    protected $table = 'school_settings';

    protected $fillable = [
        'name',
        'title',
        'email',
        'phone',
        'address',
        'running_year',
        'currency',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'created_by',
        'status',
        'is_active',
        'logo_url',
        'bg_color',
    ];
}
