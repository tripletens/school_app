<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolTermSession extends Model
{
    use HasFactory;

    protected $table = 'school_term_session';

    protected $fillable = ['name', 'slug', 'status', 'is_active'];
}
