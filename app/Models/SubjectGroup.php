<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectGroup extends Model
{
    use HasFactory;

    public $fillable = ["parent_subject_id","child_subject_id","status"];

}
