<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceMenu extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'country_id',
        'name',
        'status',
    ];
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
