<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SaleGoal extends Model
{
    use HasFactory;

    protected $casts = [

    ];

    protected $fillable = [
        'start_at',
        'end_at',
        'profitability',
        'profitability_dollar',
    ];

    public function type(): MorphTo
    {
        return $this->morphTo('type');
    }
}
