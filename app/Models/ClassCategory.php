<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassCategory extends Model
{
    use HasFactory;

    protected $table = 'class_category';
    protected $fillable = ['name', 'slug', 'status', 'is_active', 'created_by'];
}
