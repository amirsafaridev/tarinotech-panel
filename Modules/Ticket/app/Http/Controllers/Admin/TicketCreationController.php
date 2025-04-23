<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Modules\Admin\app\Models\Admin;
use Modules\Chat\app\Events\Message\NewMessage;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatBot;
use Modules\Support\app\Models\ChatMessage;
use Modules\Ticket\app\Http\Requests\Admin\Ticket\StoreRequest;
use Modules\Ticket\app\Models\TicketDetail;
use Modules\Ticket\app\Models\TicketPriority;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketSubject;
use Modules\User\app\Models\User;
use View;

class TicketCreationController extends Controller
{
    use HasJsonCommonResponseTrait;

    const CREATE_TITLE = 'تیکت ها - ایجاد';

    /**
     * Show the form for creating a new ticket.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        $statuses = TicketStatus::orderBy('order')->get();
        $priorities = TicketPriority::orderBy('level')->get();
        $subjects = TicketSubject::where('is_published', true)->get();
        $users = User::orderBy('first_name')->get();

        return view('ticket::admin.create', compact('title', 'statuses', 'priorities', 'subjects', 'users'));
    }

    /**
     * Store a newly created ticket.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create chat
            $chat = Chat::create([
                'title' => $request->input('title'),
                'type' => ChatType::Ticket,
                'status' => ChatStatus::AdminAnswer,
                'project_id' => $request->input('project_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create chat user (for the selected user)
            $chat->users()->create([
                'user_id' => $request->input('user_id'),
                'user_type' => User::class,
                'seen_at' => now(),
            ]);

            // Create chat user (for the admin/support)
            $assignedTo = $request->input('assigned_to') ? $request->input('assigned_to') : auth()->id();
            $chat->users()->create([
                'user_id' => $assignedTo,
                'user_type' => Admin::class,
                'seen_at' => now(),
            ]);

            // Create ticket details
            $ticketDetail = TicketDetail::create([
                'chat_id' => $chat->id,
                'subject_id' => $request->input('subject_id'),
                'status_id' => $request->input('status_id'),
                'priority_id' => $request->input('priority_id'),
                'assigned_to' => $assignedTo,
                'last_response_at' => now(),
            ]);

            // Create initial message
            $message = ChatMessage::create([
                'chat_id' => $chat->id,
                'user_type' => Admin::class,
                'user_id' => auth()->id(),
                'content' => $request->input('initial_message'),
            ]);

            // Send notification message
            $welcomeMessage = ChatMessage::create([
                'chat_id' => $chat->id,
                'user_type' => ChatBot::class,
                'user_id' => 1,
                'content' => sprintf('پشتیبان %s آماده پاسخگویی می باشد', auth()->user()->fullname),
            ]);

            //$htmlRender = compressHtml(View::make('support::admin.part.row-message', ['message' => $welcomeMessage, 'reverse' => false]));
            //broadcast(new NewMessage($welcomeMessage, $htmlRender));

            DB::commit();

            return $this->successResponse(route('admin.ticket.manage', $chat->id));
        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);

            return $this->exceptionResponse($exception);
        }
    }
}
