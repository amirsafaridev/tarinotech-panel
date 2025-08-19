<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Factor\app\Models\Factor;
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
        'duration',
        'created_at',
        'updated_at',
    ];

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'base_id');
    }
    public function factors(): HasMany
    {
        return $this->hasMany(Factor::class);
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
