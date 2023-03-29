<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_class';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'arm',
        'staff',
        'level',
        'category',
        'no_of_students',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff');
    }
}
