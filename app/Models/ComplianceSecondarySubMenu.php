<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class ComplianceSecondarySubMenu extends Model implements HasMedia
{
    use \Spatie\MediaLibrary\InteractsWithMedia;
    use HasFactory;
    use \App\Traits\Auditable;

    protected $fillable = [
        'document_id',
        'compliance_menu_id',
        'compliance_sub_menu_id',
        'compliance_primary_sub_menu_id',
        'country_id',
        'calendar_year_id',
        'year',
        'secondary_name',
        'renewed_date',
        'is_expired',
        'expired_date',
        'folder_type',
        'is_uploaded',
        'approve_status',
        'status',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('compliance_secondary_attachments');
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
    public function complianceSubMenu(): BelongsTo
    {
        return $this->belongsTo(ComplianceSubMenu::class, 'compliance_sub_menu_id');
    }
    public function compliancePrimarySubMenu(): BelongsTo
    {
        return $this->belongsTo(CompliancePrimarySubMenu::class, 'compliance_primary_sub_menu_id');
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
