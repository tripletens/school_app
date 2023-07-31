<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceProviders extends Model
{
    use HasFactory;

    protected $table = 'service_providers';
    protected $fillable = ['id', 'name', 'slug', 'is_active', 'created_by'];
}
