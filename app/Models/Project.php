<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'project_type_id',
        'admin_id',
        'user_id',
        'price',
        'status',
        'created_at',
        'updated_at',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function project(): MorphTo
    {
        return $this->morphTo();
    }
}
