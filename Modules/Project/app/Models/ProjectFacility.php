<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectFacility extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $casts = [
        'added_at' => 'date',
    ];

    protected $fillable = [
        'project_id',
        'facility_id',
        'price_type',
        'price_value',
        'work_cycle',
        'work_cycle_value',
        'financial_cycle',
        'financial_cycle_value',
        'status',
        'renewal_at',
        'added_at',
        'description',
    ];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_FACILITY)
            ->logAll();
    }
}
