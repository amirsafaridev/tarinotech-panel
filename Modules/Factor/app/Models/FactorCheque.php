<?php

namespace Modules\Factor\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactorCheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_date',
        'cheque_identifier',
        'cheque_file',
        'cheque_registered',
        'factor_id',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class, 'factor_id');
    }
}
