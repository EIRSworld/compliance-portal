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
        'country_id',
        'compliance_menu_id',
        'name',
        'renewed_date',
        'expired_date',
        'is_uploaded',
        'status',
    ];
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('compliance_attachments');
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
        return $this->belongsTo(User::class, 'updated_by');
    }
}
