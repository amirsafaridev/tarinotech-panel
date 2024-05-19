<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'type_id',
        'note',
    ];

    protected $appends = ['path'];

    protected function path(): Attribute
    {
        return new Attribute(
            get: function () {
                if ($this->relationLoaded('base')) {
                    return $this->type->title.' - '.$this->title;
                }

                return '';
            }
        );
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'type_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'status_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_STATUS)
            ->logAll();
    }
}
