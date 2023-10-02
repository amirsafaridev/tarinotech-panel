<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectType extends Model
{
    use HasFactory;

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function additionalFeatures(): HasMany
    {
        return $this->hasMany(AdditionalFeature::class);
    }
}
