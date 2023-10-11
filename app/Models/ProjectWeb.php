<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProjectWeb extends Model
{
    use HasFactory;

    protected $casts = [
        'domains' => 'json',
        'host' => 'json',
        'language' => 'json',
        'sample' => 'json',
    ];

    public function project(): MorphOne
    {
        return $this->morphOne(Project::class, 'project');
    }
}
