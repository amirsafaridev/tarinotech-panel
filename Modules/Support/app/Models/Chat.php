<?php

namespace Modules\Support\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Project\app\Models\Project;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'logo',
        'type',
        'status',
        'project_id',
        'created_at',
        'updated_at',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(ChatUser::class, 'chat_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'id');
    }
}
