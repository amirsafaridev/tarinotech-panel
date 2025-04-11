<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Ticket\app\Http\Requests\Admin\TicketPriority\StoreRequest;
use Modules\Ticket\app\Http\Requests\Admin\TicketPriority\UpdateRequest;
use Modules\Ticket\app\Models\TicketPriority;

class TicketPriorityController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'اولویت‌های تیکت';

    const CREATE_TITLE = 'اولویت‌های تیکت - ایجاد';

    const EDIT_TITLE = 'اولویت‌های تیکت - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $priorities = TicketPriority::query()
            ->orderBy('level')
            ->get();

        return view('ticket::admin.priorities.index', compact('title', 'priorities'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('ticket::admin.priorities.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            TicketPriority::query()->create($item);
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(TicketPriority $ticketPriority)
    {
        return view('ticket::admin.priorities.edit', [
            'title' => self::EDIT_TITLE,
            'ticketPriority' => $ticketPriority,
        ]);
    }

    public function update(UpdateRequest $request, TicketPriority $ticketPriority)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $ticketPriority->update($item);
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(TicketPriority $ticketPriority)
    {
        try {
            // Check if priority is used by any tickets
            if ($ticketPriority->tickets()->exists()) {
                return $this->errorBack('این اولویت در حال استفاده است و نمی‌توان آن را حذف کرد.');
            }

            $ticketPriority->delete();

            return $this->successDestroyBack(route('admin.ticket.priorities.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['name'] = $request->input('name');
        $item['color'] = $request->input('color');
        $item['description'] = $request->input('description');
        $item['level'] = $request->input('level', 0);
        $item['should_notify'] = $request->has('should_notify') ? 1 : 0;

        return $item;
    }
}
