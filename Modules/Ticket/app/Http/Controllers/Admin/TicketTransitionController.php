<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Ticket\app\Enums\TriggerEnum;
use Modules\Ticket\app\Http\Requests\Admin\TicketTransition\StoreRequest;
use Modules\Ticket\app\Http\Requests\Admin\TicketTransition\UpdateRequest;
use Modules\Ticket\app\Models\TicketEvent;
use Modules\Ticket\app\Models\TicketStatus;
use Modules\Ticket\app\Models\TicketTransition;

class TicketTransitionController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'انتقال خودکار وضعیت';

    const CREATE_TITLE = 'انتقال خودکار وضعیت - ایجاد';

    const EDIT_TITLE = 'انتقال خودکار وضعیت - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $transitions = TicketTransition::with(['fromStatus', 'toStatus', 'event'])
            ->orderBy('id', 'desc')
            ->get();

        return view('ticket::admin.transitions.index', compact('title', 'transitions'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;
        $statuses = TicketStatus::all();
        $events = TicketEvent::all();
        $triggers = TriggerEnum::getValues();

        return view('ticket::admin.transitions.create', compact('title', 'statuses', 'events', 'triggers'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            TicketTransition::query()->create($item);
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(TicketTransition $ticketTransition)
    {
        $title = self::EDIT_TITLE;
        $statuses = TicketStatus::all();
        $events = TicketEvent::all();
        $triggers = TriggerEnum::getValues();

        return view('ticket::admin.transitions.edit', compact('title', 'ticketTransition', 'statuses', 'events', 'triggers'));
    }

    public function update(UpdateRequest $request, TicketTransition $ticketTransition)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $ticketTransition->update($item);
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(TicketTransition $ticketTransition)
    {
        try {
            $ticketTransition->delete();

            return $this->successDestroyBack(route('admin.ticket.transitions.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        return [
            'from_status_id' => $request->input('from_status_id'),
            'to_status_id' => $request->input('to_status_id'),
            'event_id' => $request->input('event_id'),
            'days_trigger' => $request->input('days_trigger'),
            'is_active' => $request->boolean('is_active', true),
        ];
    }
}
