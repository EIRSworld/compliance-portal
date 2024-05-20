<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;

class CompliancePrimarySubMenu extends Model implements HasMedia
{
    use \Spatie\MediaLibrary\InteractsWithMedia;
    use HasFactory;
    use \App\Traits\Auditable;

    protected $fillable = [
        'document_id',
        'compliance_menu_id',
        'compliance_sub_menu_id',
        'country_id',
        'entity_id',
        'calendar_year_id',
        'assign_id',
        'year',
        'occurrence',
        'event_name',
        'description',
        'due_date',
        'event_type',
        'subject',
        'is_uploaded',
        'upload_by',
        'upload_date',
        'upload_comment',
        'approve_status',
        'approve_by',
        'approve_date',
        'reject_comment',
        'status',
        'status_text',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('compliance_primary_attachments');
        $this->addMediaCollection('mail_document');
    }


    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
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
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function uploadBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'upload_by');
    }
    public function approveBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approve_by');
    }
}
