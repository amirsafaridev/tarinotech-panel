<?php

namespace Modules\Ticket\app\Http\Controllers\Admin;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;
use Modules\Support\app\Models\Chat;
use Modules\Ticket\app\Http\Requests\Admin\UpdateRequest;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'تیکت ها';

    const CREATE_TITLE = 'تیکت ها - ایجاد';

    const EDIT_TITLE = 'تیکت ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('ticket::admin.index', compact('title', 'routeData', 'dataTable'));
    }

    public function message(Chat $chat)
    {
        $title = self::EDIT_TITLE;

        $chat->load('project');

        if ($chat->status === ChatStatus::Open) {
            $chat->users()->create([
                'user_id' => auth()->id(),
                'user_type' => Admin::class,
                'seen_at' => now(),
            ]);
            $chat->update([
                'status' => ChatStatus::AdminAnswer,
            ]);
        }

        return view('ticket::admin.message', compact('title', 'chat'));
    }

    public function update(UpdateRequest $request, Chat $chat)
    {
        try {

            $item = $this->prepareItemData($request);
            $item['slug'] = $request->input('slug');
            $chat->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Chat $chat)
    {
        try {
            $chat->delete();

            return $this->successDestroyBack(route('admin.blog.category.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.ticket.data');
    }

    public function getDataTable(): array
    {
        $dataTable = new DatatableBase();

        $dataTable
            ->addColumn(
                ColumnOption::new()
                    ->setName('id')
                    ->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('title')
                    ->setAs('عنوان')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('project.title')
                    ->setSearchable(false)
                    ->setSortable(false)
                    ->setAs('نام پروژه')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('project.domain')
                    ->setSearchable(false)
                    ->setSortable(false)
                    ->setAs('دامنه')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('ticket_admin.user.email')
                    ->setSearchable(false)
                    ->setSortable(false)
                    ->setAs('پشتیبان')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('meta.rate')
                    ->setSearchable(false)
                    ->setSortable(false)
                    ->setAs('امتیاز')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('updated_at')
                    ->setAs('آخرین پیام')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            );

        return $dataTable->render();
    }

    public function data()
    {
        try {
            $chats = Chat::query()
                ->where('type', ChatType::Ticket)
                ->with(['meta', 'project', 'ticketAdmin.user']);

            return DataTables::eloquent($chats)
                ->editColumn('updated_at', function (Chat $chat) {
                    return $chat->updated_at->toJalali()->format(formatJalaliDateTime());
                })
                ->editColumn('ticket_admin.user.email', function (Chat $chat) {
                    if ($chat->ticketAdmin) {
                        return $chat->ticketAdmin->user->email;
                    }

                    return 'در انتظار';
                })
                ->editColumn('meta.rate', function (Chat $chat) {
                    return makeUiStar($chat->meta->rate);
                })
                ->addColumn('action', function (Chat $chat) {
                    return Helper::btnMaker(BtnType::Success, route('admin.ticket.message', $chat->id), 'پاسخ');
                })
                ->rawColumns(['action', 'meta.rate'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
