<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketSubject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the ticket details with this subject
     */
    public function ticketDetails(): HasMany
    {
        return $this->hasMany(TicketDetail::class, 'subject_id');
    }
}
