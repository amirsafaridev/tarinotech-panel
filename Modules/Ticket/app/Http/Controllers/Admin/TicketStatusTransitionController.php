<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Ticket\app\Http\Requests\Admin\TicketStatusTransition\StoreRequest;
use Modules\Ticket\app\Http\Requests\Admin\TicketStatusTransition\UpdateRequest;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketStatusTransition;

class TicketStatusTransitionController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'انتقال وضعیت‌های تیکت';

    const CREATE_TITLE = 'انتقال وضعیت‌های تیکت - ایجاد';

    const EDIT_TITLE = 'انتقال وضعیت‌های تیکت - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $transitions = TicketStatusTransition::query()
            ->with(['fromStatus', 'toStatus'])
            ->orderBy('days_until_transition')
            ->get();

        return view('ticket::admin.transitions.index', compact('title', 'transitions'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;
        $statuses = TicketStatus::orderBy('order')->get();

        return view('ticket::admin.transitions.create', compact('title', 'statuses'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            TicketStatusTransition::query()->create($item);
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(TicketStatusTransition $ticketStatusTransition)
    {
        $statuses = TicketStatus::orderBy('order')->get();

        return view('ticket::admin.transitions.edit', [
            'title' => self::EDIT_TITLE,
            'ticketStatusTransition' => $ticketStatusTransition,
            'statuses' => $statuses,
        ]);
    }

    public function update(UpdateRequest $request, TicketStatusTransition $ticketStatusTransition)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $ticketStatusTransition->update($item);
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(TicketStatusTransition $ticketStatusTransition)
    {
        try {
            $ticketStatusTransition->delete();

            return $this->successDestroyBack(route('admin.ticket.transitions.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['from_status_id'] = $request->input('from_status_id');
        $item['to_status_id'] = $request->input('to_status_id');
        $item['days_until_transition'] = $request->input('days_until_transition', 0);
        $item['is_active'] = $request->has('is_active') ? 1 : 0;

        return $item;
    }
}
