<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'package_id',
        'name',
        'device_name', 
        'duration',
        'start_date',
        'end_date',
        'total_price',
        'status',
    ];
}
