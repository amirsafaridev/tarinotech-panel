<?php

namespace Modules\Support\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChatUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'seen_at',
        'user_type',
        'chat_id',
        'user_id',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }
}
