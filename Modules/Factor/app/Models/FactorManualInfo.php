<?php

namespace Modules\Factor\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactorManualInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'file',
        'factor_id',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public function factor(): BelongsTo
    {
        return $this->belongsTo(Factor::class, 'factor_id');
    }
}
