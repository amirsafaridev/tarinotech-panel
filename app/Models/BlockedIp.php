<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    protected $casts = [

    ];

    protected $fillable = [
        'start_at',
        'end_at',
        'profitability',
        'profitability_dollar',
    ];
}
