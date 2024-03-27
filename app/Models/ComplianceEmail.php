<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ComplianceEmail extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'country_id',
        'subject',
        'user_id',
        'upload_by',
    ];


    public function registerMediaCollections(): void
    {
            $this->addMediaCollection('mail_document');
    }


    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function uploadBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'upload_by');
    }
}
