<?php

namespace Modules\Factor\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Models\ProjectStatus;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FactorStatusForward extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'factor_status',
        'project_status_id',
        'project_status_forward_id',
    ];

    public function currentStatus(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_id');
    }

    public function forwardStatus(): BelongsTo
    {
        return $this->belongsTo(ProjectStatus::class, 'project_status_forward_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::FACTOR_STATUS)
            ->logAll();
    }
}
