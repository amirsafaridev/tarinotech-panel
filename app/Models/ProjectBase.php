<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectBase extends Model
{
    use HasFactory,Filterable;

    protected $fillable = ['title'];

    public function types(): HasMany
    {
        return $this->hasMany(ProjectType::class, 'project_base_id');
    }
}
