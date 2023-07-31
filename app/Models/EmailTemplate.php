<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $table = 'email_template';
    protected $fillable = [
        'name',
        'subject',
        'body',
        'status',
        'is_active',
        'created_by',
    ];
}
