<?php

namespace Modules\Project\app\Models;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'base_id',
    ];

    protected $appends = ['path'];

    protected function path(): Attribute
    {
        return new Attribute(
            get: fn () => $this->base->title.' - '.$this->title,
        );
    }

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'base_id');
    }

    public function statuses(): HasMany
    {
        return $this->hasMany(ProjectStatus::class, 'type_id');
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
