<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SaleGoal extends Model
{
    use HasFactory;
    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::SALE_GOAL)
            ->logAll();
    }
}
