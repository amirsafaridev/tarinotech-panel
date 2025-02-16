<?php

namespace Modules\Project\app\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Contract\app\Models\Signable;
use Modules\Contract\app\Models\UserSignable;
use Modules\Log\app\Enums\LogNames;
use Modules\Package\app\Models\Package;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectWeb extends Model
{
    use Filterable;
    use HasFactory;
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
        return $this->morphOne(Project::class, 'target');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function signable(): MorphOne
    {
        return $this->morphOne(Signable::class, 'target');
    }

    public function userSignable(): MorphOne
    {
        return $this->morphOne(UserSignable::class, 'target');
    }

    public function requirement(): HasOne
    {
        return $this->hasOne(ProjectWebRequirement::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_WEB)
            ->logAll();
    }
}
