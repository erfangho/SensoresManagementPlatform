<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'phone_number',
        'sub_zone_id',
        'user_id',
        'detail',
    ];
}
