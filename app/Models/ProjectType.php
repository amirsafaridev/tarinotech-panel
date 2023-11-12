<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectType extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'project_base_id',
    ];

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'project_base_id');
    }

    public function additionalFeatures(): HasMany
    {
        return $this->hasMany(Facility::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_TYPE)
            ->logAll();
    }
}
