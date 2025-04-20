<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
        'order',
    ];

    /**
     * Get the ticket details with this status
     */
    public function ticketDetails(): HasMany
    {
        return $this->hasMany(TicketDetail::class, 'status_id');
    }
}
