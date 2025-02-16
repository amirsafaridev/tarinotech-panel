<?php

namespace Modules\Support\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'rate',
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'id');
    }
}
