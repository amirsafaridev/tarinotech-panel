<?php

namespace App\Models;

use App\Traits\Filterable;
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
    use Filterable;

    protected $fillable = [
        'title',
        'admin_id',
        'identify',
        'final_price',
        'transaction_id',
        'project_id',
        'status',
        'is_official',
        'expired_at',
        'gateway_data',
    ];

    protected $casts = [
        'gateway_data' => 'json',
        'expired_at' => 'date',
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

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function identifiable(): string
    {
        $this->isInt = true;

        return 'identify';
    }
}
