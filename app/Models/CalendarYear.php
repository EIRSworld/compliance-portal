<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'start_date',
        'end_date',
        'status',
    ];
    protected $casts = [
        'country_id' => 'array',
    ];
}
