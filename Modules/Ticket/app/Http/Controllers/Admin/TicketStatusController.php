<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Ticket\app\Http\Requests\Admin\TicketStatus\StoreRequest;
use Modules\Ticket\app\Http\Requests\Admin\TicketStatus\UpdateRequest;
use Modules\Ticket\app\Models\TicketStatus;

class TicketStatusController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'وضعیت‌های تیکت';

    const CREATE_TITLE = 'وضعیت‌های تیکت - ایجاد';

    const EDIT_TITLE = 'وضعیت‌های تیکت - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $statuses = TicketStatus::query()
            ->orderBy('order')
            ->get();

        return view('ticket::admin.statuses.index', compact('title', 'statuses'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('ticket::admin.statuses.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            TicketStatus::query()->create($item);
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(TicketStatus $ticketStatus)
    {
        return view('ticket::admin.statuses.edit', [
            'title' => self::EDIT_TITLE,
            'ticketStatus' => $ticketStatus,
        ]);
    }

    public function update(UpdateRequest $request, TicketStatus $ticketStatus)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $ticketStatus->update($item);
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(TicketStatus $ticketStatus)
    {
        try {
            // Check if status is used by any tickets
            if ($ticketStatus->tickets()->exists()) {
                return $this->errorBack('این وضعیت در حال استفاده است و نمی‌توان آن را حذف کرد.');
            }

            $ticketStatus->delete();

            return $this->successDestroyBack(route('admin.ticket.statuses.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['name'] = $request->input('name');
        $item['color'] = $request->input('color');
        $item['description'] = $request->input('description');
        $item['order'] = $request->input('order', 0);
        $item['is_auto_changing'] = $request->has('is_auto_changing') ? 1 : 0;

        return $item;
    }
}
