<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Student;

use App\Models\SchoolClass;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'data',
        'full_name',
        'role',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function role()
    // {
    //     return $this->belongsTo(Role::class);
    // }

    public function bio()
    {
        return $this->hasOne(UserBio::class, 'uid');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'uid');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'uid');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id');
    }

    public function school_class()
    {
        return $this->hasOne(SchoolClass::class, 'staff');
    }
}
