<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProjectAds extends Model
{
    use HasFactory;

    public function project(): MorphOne
    {
        return $this->morphOne(Project::class, 'project');
    }
}
