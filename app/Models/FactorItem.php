<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    ];

    use HasFactory;

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class);
    }

    public function transactionCategory(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::FACTOR_ITEM)
            ->logAll();
    }
}
