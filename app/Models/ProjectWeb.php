<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectWeb extends Model
{
    use HasFactory;
    use Filterable;
    use LogsActivity;

    protected $fillable = [
        'field_activity',
        'package_id',
        'project_type_id',
        'pages',
        'domains',
        'host',
        'language',
        'sample',
        'facilities',
        'working_days',
    ];

    protected $casts = [
        'domains' => 'json',
        'host' => 'json',
        'language' => 'json',
        'sample' => 'json',
        'facilities' => 'json',
        'agreement_at' => 'date',
    ];

    public function project(): MorphOne
    {
        return $this->morphOne(Project::class, 'type');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_WEB)
            ->logAll();
    }
}
