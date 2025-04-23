<?php

namespace Modules\Support\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\app\Models\Admin;
use Modules\Project\app\Models\Project;
use Modules\Ticket\app\Models\TicketDetail;

class Chat extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public function ticketAdmin(): HasOne
    {
        return $this->hasOne(ChatUser::class, 'chat_id')
            ->where('user_type', Admin::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function meta(): HasOne
    {
        return $this->hasOne(ChatMeta::class, 'chat_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    /**
     * Get the ticket details associated with this chat.
     */
    public function ticketDetail(): HasOne
    {
        return $this->hasOne(TicketDetail::class, 'chat_id');
    }
}
