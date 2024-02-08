<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ComplianceSubMenu extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'document_id',
        'country_id',
        'compliance_menu_id',
        'calendar_year_id',
        'year',
        'name',
        'renewed_date',
        'expired_date',
        'is_uploaded',
        'approve_status',
        'status',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('compliance_attachments');
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
    public function calendarYear(): BelongsTo
    {
        return $this->belongsTo(CalendarYear::class, 'calendar_year_id');
    }
    public function complianceMenu(): BelongsTo
    {
        return $this->belongsTo(ComplianceMenu::class, 'compliance_menu_id');
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
