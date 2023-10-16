<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factor extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'project_id',
        'status',
        'is_official',
        'expired_at',
        'gateway_data',
    ];

    protected $casts = [
        'gateway_data' => 'json',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(FactorItem::class);
    }
}
