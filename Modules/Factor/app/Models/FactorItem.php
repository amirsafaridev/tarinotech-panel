<?php

namespace Modules\Factor\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FactorItem extends Model
{
    use LogsActivity;

    protected $fillable = [
        'factor_id',
        'title',
        'transaction_category_id',
        'price',
        'tax_rate',
        'tax_amount',
        'discount',
        'final_price',
        'targetable_type',
        'targetable_id',
    ];

    use HasFactory;

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::FACTOR_ITEM)
            ->logAll();
    }

    public function targetable(): MorphTo
    {
        return $this->morphTo();
    }
}
