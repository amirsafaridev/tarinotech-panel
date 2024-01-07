<?php

namespace Modules\Support\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'parent_id',
        'user_type',
        'user_id',
        'content',
        'created_at',
        'updated_at',
    ];

    public function attachments(): HasMany
    {
        return $this->hasMany(ChatMessageAttachment::class, 'chat_message_id');
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function replay(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class, 'parent_id');
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
