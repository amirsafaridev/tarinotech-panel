<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Support\app\Models\Chat;
use Modules\Ticket\app\Models\TicketPriority;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketSubject;

class TicketEditorController extends Controller
{
    use HasJsonCommonResponseTrait;

    /**
     * Show the form for editing a ticket.
     *
     * @return View|RedirectResponse
     */
    public function edit(Chat $chat)
    {
        $ticketDetail = $chat->ticketDetail;

        if (! $ticketDetail) {
            return redirect()->route('admin.ticket.index')->with('danger', 'جزئیات تیکت یافت نشد');
        }

        $title = 'ویرایش تیکت - '.$chat->title;

        $statuses = TicketStatus::orderBy('order')->get();
        $priorities = TicketPriority::orderBy('level')->get();
        $subjects = TicketSubject::where('is_published', true)->get();

        return view('ticket::admin.edit', compact('title', 'chat', 'ticketDetail', 'statuses', 'priorities', 'subjects'));
    }

    /**
     * Update the specified ticket.
     *
     * @return JsonResponse
     */
    public function update(Request $request, Chat $chat)
    {
        $ticketDetail = $chat->ticketDetail;

        $request->validate([
            'title' => 'required|string|max:255',
            'status_id' => 'required|exists:ticket_statuses,id',
            'priority_id' => 'required|exists:ticket_priorities,id',
            'subject_id' => 'required|exists:ticket_subjects,id',
        ]);

        try {
            DB::beginTransaction();

            // Update the chat title
            $chat->update([
                'title' => $request->input('title'),
            ]);

            // Update ticket details
            $ticketDetail->update([
                'subject_id' => $request->input('subject_id'),
                'status_id' => $request->input('status_id'),
                'priority_id' => $request->input('priority_id'),
            ]);

            DB::commit();

            return $this->successResponse(route('admin.ticket.manage', $chat->id));

        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);

            return $this->exceptionResponse($exception);
        }
    }
}
