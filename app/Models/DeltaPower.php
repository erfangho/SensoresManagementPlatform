<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeltaPower extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'device_id',
        'detail',
        'first_order_id',
        'second_order_id',
    ];
}
