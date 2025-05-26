<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Log\app\Enums\LogNames;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Facility extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'base_id',
        'customer_extra_unit',
        'expert_extra_unit',
        'created_at',
        'updated_at',
    ];

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'base_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_facility')
            ->withTimestamps()
            ->withPivot(['renewal_at', 'id']);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::FACILITY)
            ->logAll();
    }
}
