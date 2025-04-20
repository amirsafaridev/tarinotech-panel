<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Admin\app\Models\Admin;
use Modules\Support\app\Models\Chat;

class ChatTicketDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'subject_id',
        'status_id',
        'priority_id',
        'assigned_to',
        'last_response_at',
        'closed_at',
        'rating',
        'rated_at',
        'rating_comment',
    ];

    protected $casts = [
        'last_response_at' => 'datetime',
        'closed_at' => 'datetime',
        'rated_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Get the chat that owns this ticket detail.
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    /**
     * Get the status of this ticket.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    /**
     * Get the priority of this ticket.
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(TicketPriority::class, 'priority_id');
    }

    /**
     * Get the admin assigned to this ticket.
     */
    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }

    /**
     * Get the subject that owns the chat ticket detail.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(TicketSubject::class, 'subject_id');
    }
}
