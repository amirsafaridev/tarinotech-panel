<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Modules\Support\app\Models\Chat;

class TicketManagementController extends Controller
{
    use HasJsonCommonResponseTrait;

    const SHOW_TITLE = 'نمایش';

    /**
     * Display the chat interface for a ticket.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function manage(Chat $chat)
    {
        $ticketDetail = $chat->ticketDetail;

        if (! $ticketDetail) {
            return redirect()->route('admin.ticket.index')->with('danger', 'جزئیات تیکت یافت نشد');
        }

        if ($ticketDetail->assigned_to === null) {
            $ticketDetail->update([
                'assigned_to' => auth()->id(),
            ]);
        }

        $title = self::SHOW_TITLE.' - '.$chat->title;

        $this->getChatWithRelation($chat);

        return view('ticket::admin.message', compact('title', 'chat', 'ticketDetail'));
    }

    /**
     * Load chat relationships.
     */
    protected function getChatWithRelation(Chat $chat): void
    {
        $chat->load([
            'ticketDetail.status',
            'ticketDetail.priority',
            'ticketDetail.subject',
            'ticketDetail.assignedAdmin',
            'users.user',
            'messages.user',
        ]);
    }
}
