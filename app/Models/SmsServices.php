<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsServices extends Model
{
    use HasFactory;

    protected $table = 'sms_services';
    protected $fillable = [
        'id',
        'provider_name',
        'name',
        'code',
        'username',
        'sender_id',
        'is_active',
        'api_key',
        'token_key',
        'created_by',
    ];
}
