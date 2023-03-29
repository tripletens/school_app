<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'first_name',
        'other_name',
        'surname',
        'phone',
        'uid',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uid', 'id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id');
    }
}
