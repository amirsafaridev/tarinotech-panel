<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Ticket\app\Http\Requests\Admin\TicketSubject\StoreRequest;
use Modules\Ticket\app\Http\Requests\Admin\TicketSubject\UpdateRequest;
use Modules\Ticket\app\Models\TicketSubject;

class TicketSubjectController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'موضوعات تیکت';

    const CREATE_TITLE = 'موضوعات تیکت - ایجاد';

    const EDIT_TITLE = 'موضوعات تیکت - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;
        $subjects = TicketSubject::withTrashed()
            ->orderBy('id', 'desc')
            ->get();

        return view('ticket::admin.subjects.index', compact('title', 'subjects'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('ticket::admin.subjects.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            TicketSubject::query()->create($item);
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(TicketSubject $ticketSubject)
    {
        return view('ticket::admin.subjects.edit', [
            'title' => self::EDIT_TITLE,
            'ticketSubject' => $ticketSubject,
        ]);
    }

    public function update(UpdateRequest $request, TicketSubject $ticketSubject)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $ticketSubject->update($item);
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(TicketSubject $ticketSubject)
    {
        try {
            $ticketSubject->delete();

            return $this->successDestroyBack(route('admin.ticket.subjects.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    public function restore($id)
    {
        try {
            $subject = TicketSubject::withTrashed()->findOrFail($id);
            $subject->restore();

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['is_published'] = $request->has('is_published') ? 1 : 0;

        return $item;
    }
}
