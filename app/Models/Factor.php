<?php

namespace App\Models;

use App\Traits\HasUniqueIdentifyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Factor extends Model
{
    use HasFactory;
    use HasUniqueIdentifyTrait;

    protected $fillable = [
        'title',
        'admin_id',
        'identify',
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

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Project::class, 'id', 'id', 'project_id', 'user_id');
    }

    public function identifiable(): string
    {
        $this->isInt = true;

        return 'identify';
    }
}
