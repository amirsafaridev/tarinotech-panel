<?php

namespace Modules\Project\app\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectOptionSet extends Model
{
    use Filterable;
    use LogsActivity;

    protected $fillable = [
        'target_id',
        'target_type',
        'project_option_id',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(ProjectOption::class, 'project_option_id');
    }

    public function target(): MorphTo
    {
        return $this->morphTo('target');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_WEB)
            ->logAll();
    }
}
