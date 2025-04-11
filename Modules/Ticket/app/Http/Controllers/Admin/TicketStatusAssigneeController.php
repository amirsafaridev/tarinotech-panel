<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Ticket\app\Http\Requests\Admin\TicketStatusAssignee\StoreRequest;
use Modules\Ticket\app\Http\Requests\Admin\TicketStatusAssignee\UpdateRequest;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketStatusAssignee;
use Spatie\Permission\Models\Role;

class TicketStatusAssigneeController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'مسئولین وضعیت‌های تیکت';

    const CREATE_TITLE = 'مسئولین وضعیت‌های تیکت - ایجاد';

    const EDIT_TITLE = 'مسئولین وضعیت‌های تیکت - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $assignees = TicketStatusAssignee::with(['status', 'role'])
            ->get();

        return view('ticket::admin.assignees.index', compact('title', 'assignees'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;
        $statuses = TicketStatus::orderBy('order')->get();
        $roles = Role::where('guard_name', 'admin')->get();

        // Get statuses that are already assigned to avoid duplicates
        $assignedStatusIds = TicketStatusAssignee::pluck('status_id')->toArray();
        $availableStatuses = $statuses->whereNotIn('id', $assignedStatusIds);

        return view('ticket::admin.assignees.create', compact('title', 'availableStatuses', 'roles'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            TicketStatusAssignee::query()->create($item);
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(TicketStatusAssignee $ticketStatusAssignee)
    {
        $statuses = TicketStatus::orderBy('order')->get();
        $roles = Role::where('guard_name', 'admin')->get();

        return view('ticket::admin.assignees.edit', [
            'title' => self::EDIT_TITLE,
            'ticketStatusAssignee' => $ticketStatusAssignee,
            'statuses' => $statuses,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateRequest $request, TicketStatusAssignee $ticketStatusAssignee)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $ticketStatusAssignee->update($item);
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(TicketStatusAssignee $ticketStatusAssignee)
    {
        try {
            $ticketStatusAssignee->delete();

            return $this->successDestroyBack(route('admin.ticket.assignees.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['status_id'] = $request->input('status_id');
        $item['role_id'] = $request->input('role_id');

        return $item;
    }
}
