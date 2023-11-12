<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ProjectStatus extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'project_type_id',
        'note',
    ];

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'project_base_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_STATUS)
            ->logAll();
    }
}
