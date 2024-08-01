<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Contract\app\Models\Signable;
use Modules\Contract\app\Models\UserSignable;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectAds extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'field_activity',
        'designed_by',
    ];

    public function project(): MorphOne
    {
        return $this->morphOne(Project::class, 'target');
    }

    public function signable(): MorphOne
    {
        return $this->morphOne(Signable::class, 'target');
    }

    public function userSignable(): MorphOne
    {
        return $this->morphOne(UserSignable::class, 'target');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_ADS)
            ->logAll();
    }
}
