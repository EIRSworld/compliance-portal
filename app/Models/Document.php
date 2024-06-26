<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'name',
        'country_id',
        'entity_id',
        'calendar_year_id',
        'status',
    ];
    protected $casts = [
        'entity_id' => 'array',
    ];

    public function complianceSub(): HasMany
    {
        return $this->hasMany(ComplianceSubMenu::class, 'compliance_menu_id', 'id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function calendarYear(): BelongsTo
    {
        return $this->belongsTo(CalendarYear::class, 'calendar_year_id');
    }
}
