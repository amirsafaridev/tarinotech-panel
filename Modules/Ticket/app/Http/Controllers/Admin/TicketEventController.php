<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Modules\Ticket\app\Models\TicketEvent;

class TicketEventController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'رویدادهای تیکت';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $events = TicketEvent::query()
            ->orderBy('id', 'desc')
            ->get();

        return view('ticket::admin.events.index', compact('title', 'events'));
    }
}
