<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarqueeMsg extends Model
{
    use HasFactory;

    protected $table = 'marquee_msg';
    protected $fillable = ['id', 'name', 'msg'];
}
