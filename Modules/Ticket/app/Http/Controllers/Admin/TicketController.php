<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\app\Models\Admin;
use Modules\Chat\app\Events\Message\NewMessage;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatBot;
use Modules\Support\app\Models\ChatMessage;
use Modules\Ticket\app\Exports\Admin\Report\TicketDatatableExport;
use Modules\Ticket\app\Filters\Ticket\DateFilter;
use Modules\Ticket\app\Filters\Ticket\PriorityFilter;
use Modules\Ticket\app\Filters\Ticket\SearchFilter;
use Modules\Ticket\app\Filters\Ticket\SortFilter;
use Modules\Ticket\app\Filters\Ticket\StatusFilter;
use Modules\Ticket\app\Filters\Ticket\SubjectFilter;
use Modules\Ticket\app\Http\Requests\Admin\Ticket\StoreRequest;
use Modules\Ticket\app\Models\TicketDetail;
use Modules\Ticket\app\Models\TicketPriority;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketSubject;
use Modules\User\app\Models\User;
use View;

class TicketController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'تیکت ها';

    const SHOW_TITLE = 'نمایش';

    const CREATE_TITLE = 'تیکت ها - ایجاد';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $selectedColumns = collect([
            'ticket_details.id',
            'chats.id as chat_id',
            'chats.title',
            'ticket_details.assigned_to',
            'ticket_details.last_response_at',
            'ticket_details.rating',
            'admins.first_name as admin_first_name',
            'admins.last_name as admin_last_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
            'ticket_statuses.name as status_name',
            'ticket_statuses.color as status_color',
            'ticket_priorities.name as priority_name',
            'ticket_priorities.color as priority_color',
            'ticket_subjects.title as subject_title',
        ]);

        $tickets = TicketDetail::query()
            ->select($selectedColumns->toArray())
            ->join('chats', 'ticket_details.chat_id', '=', 'chats.id')
            ->join('ticket_statuses', 'ticket_details.status_id', '=', 'ticket_statuses.id')
            ->join('ticket_priorities', 'ticket_details.priority_id', '=', 'ticket_priorities.id')
            ->join('ticket_subjects', 'ticket_details.subject_id', '=', 'ticket_subjects.id')
            ->leftJoin('admins', 'ticket_details.assigned_to', '=', 'admins.id')
            ->join('chat_users', function ($join) {
                $join->on('chats.id', '=', 'chat_users.chat_id')
                    ->where('chat_users.user_type', '<>', Admin::class);
            })
            ->join('users', function ($join) {
                $join->on('chat_users.user_id', '=', 'users.id')
                    ->where('chat_users.user_type', '<>', Admin::class);
            })
            ->where(function ($query) {
                $query->whereNull('ticket_details.assigned_to')
                    ->orWhere('ticket_details.assigned_to', auth()->id());
            })
            ->filter([
                SearchFilter::class,
                AdminJoinedFilter::class,
                StatusFilter::class,
                PriorityFilter::class,
                SubjectFilter::class,
                DateFilter::class,
                SortFilter::class,
            ]);

        if (request('export')) {
            return $this->export($tickets->get());
        }

        $tickets = $tickets->paginate()
            ->withQueryString();

        return view('ticket::admin.index', compact('title', 'tickets'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        $statuses = TicketStatus::orderBy('order')->get();
        $priorities = TicketPriority::orderBy('level')->get();
        $subjects = TicketSubject::where('is_published', true)->get();
        $users = User::orderBy('first_name')->get();

        return view('ticket::admin.create', compact('title', 'statuses', 'priorities', 'subjects', 'users'));
    }

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

    private function export($tickets)
    {
        try {
            $fileName = 'Ticket-'.Carbon::now()->format('Y-m-d').'.xlsx';

            return Excel::download(new TicketDatatableExport(collect($tickets)), $fileName);
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', 'خطا در هنگام صادر کردن اطلاعات');
        }
    }

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

        return view('ticket::admin.show', compact('title', 'chat', 'ticketDetail'));
    }

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

    public function update(Request $request, Chat $chat)
    {
        $ticketDetail = $chat->ticketDetail;

        if (! $ticketDetail) {
            return redirect()->route('admin.ticket.index')->with('danger', 'جزئیات تیکت یافت نشد');
        }

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

            return redirect()->route('admin.ticket.manage', $chat->id)
                ->with('success', 'تیکت با موفقیت بروزرسانی شد');

        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);

            return redirect()->back()->with('danger', 'خطا در بروزرسانی تیکت');
        }
    }
}
