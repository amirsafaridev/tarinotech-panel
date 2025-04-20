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
     * Get the chat ticket details with this status
     */
    public function chatTicketDetails(): HasMany
    {
        return $this->hasMany(ChatTicketDetail::class, 'status_id');
    }
}
