<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBio extends Model
{
    use HasFactory;

    protected $table = 'user_bio';

    protected $fillable = [
        'first_name',
        'last_name',
        'other_name',
        'surname',
        'password',
        'phone',
        'uid',
        'role',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'uid');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'role');
    }
}
