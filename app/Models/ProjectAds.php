<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
        return $this->morphOne(Project::class, 'type');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_ADS)
            ->logAll();
    }
}
