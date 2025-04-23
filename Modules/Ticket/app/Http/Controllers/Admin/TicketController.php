<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use Exception;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\app\Models\Admin;
use Modules\Ticket\app\Exports\Admin\Report\TicketDatatableExport;
use Modules\Ticket\app\Filters\Ticket\DateFilter;
use Modules\Ticket\app\Filters\Ticket\PriorityFilter;
use Modules\Ticket\app\Filters\Ticket\SearchFilter;
use Modules\Ticket\app\Filters\Ticket\SortFilter;
use Modules\Ticket\app\Filters\Ticket\StatusFilter;
use Modules\Ticket\app\Filters\Ticket\SubjectFilter;
use Modules\Ticket\app\Models\TicketDetail;

class TicketController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'تیکت ها';

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
}
