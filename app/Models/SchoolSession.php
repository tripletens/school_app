<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolTermSession;

class SchoolSession extends Model
{
    use HasFactory;

    protected $table = 'school_session';

    protected $fillable = [
        'school_session',
        'school_term',
        'start_date',
        'end_date',
        'day_duration',
        'school_population',
        'vacation',
        'holiday',
        'status',
        'is_active',
    ];

    public function term()
    {
        return $this->hasOne(SchoolTermSession::class, 'id');
    }
}
