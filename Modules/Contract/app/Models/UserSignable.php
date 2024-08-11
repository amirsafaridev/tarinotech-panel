<?php

namespace Modules\Contract\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserSignable extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_type',
        'target_id',
        'user_id',
        'status',
        'note',
    ];

    public function target(): MorphTo
    {
        return $this->morphTo();
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(SignableAttachment::class, 'target');
    }
}
