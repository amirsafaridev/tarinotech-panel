<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;
use Modules\Support\app\Models\Chat;

class TicketManagementController extends Controller
{
    use HasJsonCommonResponseTrait;

    const SHOW_TITLE = 'نمایش';

    /**
     * Display the chat interface for a ticket.
     *
     * @return View|RedirectResponse
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

        // Get available admins for reassignment
        $availableAdmins = $this->getAvailableAdmins();

        $this->getChatWithRelation($chat);

        return view('ticket::admin.message', compact('title', 'chat', 'ticketDetail', 'availableAdmins'));
    }

    /**
     * Reassign the ticket to another admin.
     *
     * @return JsonResponse
     */
    public function reassign(Request $request, Chat $chat)
    {
        $request->validate([
            'admin_id' => 'required|exists:admins,id',
        ]);

        $ticketDetail = $chat->ticketDetail;

        if (! $ticketDetail) {
            return $this->failure('جزئیات تیکت یافت نشد', 404);
        }

        try {
            // Update assigned admin
            $ticketDetail->update([
                'assigned_to' => $request->input('admin_id'),
            ]);

            return $this->successResponse(route('admin.ticket.manage', $chat->id));
        } catch (Exception $exception) {
            report($exception);

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Get all available admins for ticket assignment.
     *
     * @return Collection
     */
    protected function getAvailableAdmins()
    {
        return Admin::query()
            ->select(['id', 'first_name', 'last_name', 'job_title_id'])
            ->with('jobTitle:id,title')
            ->orderBy('first_name')
            ->get()
            ->map(function ($admin) {
                $admin->full_name = $admin->first_name.' '.$admin->last_name;
                $admin->display_name = $admin->full_name.($admin->jobTitle ? ' ('.$admin->jobTitle->title.')' : '');

                return $admin;
            });
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
