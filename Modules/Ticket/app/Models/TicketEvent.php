<?php

namespace Modules\Ticket\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trigger',
    ];
}
