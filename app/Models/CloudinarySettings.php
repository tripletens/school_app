<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloudinarySettings extends Model
{
    use HasFactory;

    protected $table = 'cloudinary_settings';
    protected $fillable = [
        'name',
        'api_key',
        'secret_key',
        'cloudinary_url',
        'is_active',
        'created_by',
    ];
}
