<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketPriority extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'description',
        'should_notify',
        'level',
    ];

    protected $casts = [
        'should_notify' => 'boolean',
    ];

    /**
     * Define the relationship with tickets
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'priority_id');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Modules\Ticket\database\factories\TicketPriorityFactory::new();
    }
}
