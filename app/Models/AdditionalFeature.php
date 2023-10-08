<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdditionalFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'project_type_id',
        'created_at',
        'updated_at',
    ];

    public function base(): BelongsTo
    {
        return $this->belongsTo(ProjectBase::class, 'project_base_id');
    }
}
