<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassLevel extends Model
{
    use HasFactory;

    protected $table = 'class_level';
    protected $fillable = ['name', 'slug', 'status', 'is_active', 'created_by'];
}
