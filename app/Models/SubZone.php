<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'zone_id',
        'detail',
    ];
}
