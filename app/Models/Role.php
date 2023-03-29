<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    protected $fillable = ['id', 'name', 'slug'];

    // public function user(): BelongsTo
    // {
    //     return $this->hasMany(User::class);
    // }

    public function staff()
    {
        return $this->belongsTo(User::class, 'role', 'id');
    }
}
