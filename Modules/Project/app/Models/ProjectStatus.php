<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Enums\ProjectBase as ProjectBaseEnum;
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
                if ($this->relationLoaded('type')) {
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

    public function scopeWebBase($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('base_id', ProjectBaseEnum::Web);
        });
    }

    public function scopeSeoBase($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('base_id', ProjectBaseEnum::Seo);
        });
    }

    public function scopeAdsBase($query)
    {
        return $query->whereHas('type', function ($q) {
            $q->where('base_id', ProjectBaseEnum::Ads);
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PROJECT_STATUS)
            ->logAll();
    }
}
