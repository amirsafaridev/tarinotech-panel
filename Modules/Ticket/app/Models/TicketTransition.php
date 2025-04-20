<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_status_id',
        'to_status_id',
        'event_id',
        'days_trigger',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the from status associated with the transition.
     */
    public function fromStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'from_status_id');
    }

    /**
     * Get the to status associated with the transition.
     */
    public function toStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'to_status_id');
    }

    /**
     * Get the event associated with the transition.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(TicketEvent::class, 'event_id');
    }
}
