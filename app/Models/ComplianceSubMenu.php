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
        'compliance_menu_id',
        'sub_menu_id',
        'country_id',
        'calendar_year_id',
        'year',
        'sub_menu_name',
        'renewed_date',
        'is_expired',
        'expired_date',
        'folder_type',
        'is_uploaded',
        'upload_comment',
        'approve_status',
        'reject_comment',
        'status',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('compliance_attachments');
    }

//    public function subMenu(): BelongsTo
//    {
//        return $this->belongsTo(ComplianceSubMenu::class, 'sub_menu_id');
//    }
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
