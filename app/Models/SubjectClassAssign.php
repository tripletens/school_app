<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectClassAssign extends Model
{
    use HasFactory;
    public $fillable = ['subject_id', 'class_id'];
}
