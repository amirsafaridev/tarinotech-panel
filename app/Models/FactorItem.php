<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactorItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'factor_id',
        'title',
        'transaction_category_id',
        'price',
        'tax',
        'discount',
        'tax_amount',
        'final_price',
    ];

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class);
    }

    public function transactionCategory(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }
}
