<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'name',
        'country_id',
        'calendar_year_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function calendarYear(): BelongsTo
    {
        return $this->belongsTo(CalendarYear::class, 'calendar_year_id');
    }
}
