<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectType extends Model
{
    use HasFactory;

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
}
