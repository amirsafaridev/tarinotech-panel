<?php

namespace Modules\Support\app\Http\Controllers\Admin;

use App\Enums\Database\SampleMessage\MessageType;
use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Support\app\Http\Requests\Admin\SampleMessage\MessageRequest;
use Modules\Support\app\Http\Requests\Admin\SampleMessage\StoreRequest;
use Modules\Support\app\Http\Requests\Admin\SampleMessage\UpdateRequest;
use Modules\Support\app\Models\SampleMessage;
use View;
use Yajra\DataTables\Facades\DataTables;

class SampleMessageController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پیام های آماده';

    const CREATE_TITLE = 'پیام های آماده - ایجاد';

    const EDIT_TITLE = 'پیام های آماده - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('support::admin.sample_message.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('support::admin.sample_message.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            SampleMessage::query()->create($this->prepareItemData($request));

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function edit(SampleMessage $sampleMessage)
    {
        $title = self::EDIT_TITLE;

        return view('support::admin.sample_message.edit', compact('title', 'sampleMessage'));
    }

    public function update(UpdateRequest $request, SampleMessage $sampleMessage)
    {
        try {
            $sampleMessage->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(SampleMessage $sampleMessage)
    {
        try {
            $sampleMessage->delete();

            return $this->successDestroyBack(route('admin.support.sample-message.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    public function message(MessageRequest $request)
    {
        $sampleMessages = SampleMessage::query()
            ->when($request->input('search'), function ($q) use ($request) {
                $q->where('message', 'like', '%'.$request->input('search').'%');
                $q->orWhere('title', 'like', '%'.$request->input('search').'%');
            })
            ->limit(20)
            ->get();

        $sampleMessages = $sampleMessages->map(function (SampleMessage $sampleMessage) {
            $data = $sampleMessage;
            $data['htmlRender'] = compressHtml(View::make('support::admin.part.row-sample-message', ['sampleMessage' => $sampleMessage]));

            return $data;
        });

        return [
            'success' => true,
            'sampleMessages' => $sampleMessages,
        ];
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['message'] = $request->input('message');
        $item['type'] = MessageType::ReadyMessage;

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.support.sample-message.data');
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
                    ->setName('created_at')
                    ->setAs('ایجاد')
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
            $sampleMessages = SampleMessage::query();

            return DataTables::eloquent($sampleMessages)
                ->editColumn('created_at', function (SampleMessage $sampleMessage) {
                    return $sampleMessage->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (SampleMessage $sampleMessage) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.support.sample-message.edit', $sampleMessage->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
