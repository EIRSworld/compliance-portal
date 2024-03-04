<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class UploadDocument extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'document_id',
        'compliance_menu_id',
        'compliance_sub_menu_id',
        'compliance_primary_sub_menu_id',
        'country_id',
        'calendar_year_id',
        'year',
        'name',
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
        $this->addMediaCollection('upload_documents');
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


}
