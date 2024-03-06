<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ComplianceMenu extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'document_id',
        'country_id',
        'calendar_year_id',
        'year',
        'name',
        'folder_type',
        'is_expired',
        'expired_date',
        'is_uploaded',
        'upload_by',
        'upload_date',
        'upload_comment',
        'approve_status',
        'approve_by',
        'approve_date',
        'reject_comment',
        'status',
    ];


//    public function registerMediaCollections(): void
//    {
//        $this->addMediaCollection('compliance_documents');
//    }


    public function complianceSubMenus()
    {
        return $this->hasMany(ComplianceSubMenu::class, 'compliance_menu_id');
    }
    public function compliancePrimarySubMenus(): HasMany
    {
        return $this->hasMany(CompliancePrimarySubMenu::class, 'compliance_menu_id');
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
    public function calendarYear(): BelongsTo
    {
        return $this->belongsTo(CalendarYear::class, 'calendar_year_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function uploadBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'upload_by');
    }
}
