<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketStatusTransition extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_status_id',
        'to_status_id',
        'days_until_transition',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the "from" status for this transition.
     */
    public function fromStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'from_status_id');
    }

    /**
     * Get the "to" status for this transition.
     */
    public function toStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'to_status_id');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Modules\Ticket\database\factories\TicketStatusTransitionFactory::new();
    }
}
