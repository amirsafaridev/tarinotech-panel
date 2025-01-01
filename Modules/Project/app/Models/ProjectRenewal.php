<?php

namespace Modules\Project\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Factor\app\Models\FactorItem;

class ProjectRenewal extends Model
{
    use HasFactory;

    private static array $defaultWith = [
        'project' => [
            'user:id,person_type,official_bill',
            'type:id,title',
            'admin:first_name,last_name,id',
        ],
    ];

    protected $fillable = [
        'package_price',
        'project_price',
        'package_calculated_price',
        'status',
        'project_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function factorItems(): MorphMany
    {
        return $this->morphMany(FactorItem::class, 'targetable');
    }

    public function facilities(): HasMany
    {
        return $this->hasMany(ProjectFacilityRenewal::class)
            ->with('facility');
    }

    public function scopeWithProjectRelations(Builder $query, array $relations = []): Builder
    {
        // Use provided relations if any, otherwise use defaults
        $withRelations = ! empty($relations) ? $relations : self::$defaultWith;

        return $query->has('project.user')
            ->with($withRelations);
    }

    public static function findWithRelations(int $id, array $relations = []): self
    {
        return static::withProjectRelations($relations)
            ->findOrFail($id);
    }
}
