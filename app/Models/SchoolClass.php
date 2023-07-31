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
        'slug',
        'arm',
        'staff',
        'class_level',
        'class_category',
        'total_students',
        'status',
        'report_card_template',
        'mid_term_template',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff');
    }

    public function level()
    {
        return $this->hasOne(ClassLevel::class, 'id', 'class_level');
    }

    public function category()
    {
        return $this->hasOne(ClassCategory::class, 'id', 'class_category');
    }
}
