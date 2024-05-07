<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entity extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        'entity_name',
        'country_id',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
