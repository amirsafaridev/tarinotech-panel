<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Admin\app\Models\Admin;

class TicketPriority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
        'should_notify',
        'level',
        'admin_support_id',
        'message',
    ];

    protected $casts = [
        'should_notify' => 'boolean',
    ];

    /**
     * Get the chat ticket details with this priority
     */
    public function chatTicketDetails(): HasMany
    {
        return $this->hasMany(ChatTicketDetail::class, 'priority_id');
    }

    /**
     * Get the admin support associated with this priority
     */
    public function adminSupport(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_support_id');
    }
}
