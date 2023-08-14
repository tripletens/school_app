<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSubjectAssign extends Model
{
    use HasFactory;

    public $fillable = ['staff_id', 'class_id'];
}
