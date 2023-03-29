<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'student';

    protected $fillable = [
        'first_name',
        'other_name',
        'surname',
        'phone',
        'uid',
        'role',
        'dob',
        'sport_house',
        'admission_number',
        'gender',
        'student_class',
    ];
}
