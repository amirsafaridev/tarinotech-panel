<?php

namespace Modules\FreeDay\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\FreeDay\app\Http\Requests\Admin\StoreRequest;
use Modules\FreeDay\app\Http\Requests\Admin\UpdateRequest;
use Modules\FreeDay\app\Models\FreeDay;
use Yajra\DataTables\Facades\DataTables;

class FreeDayController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'تقویم تعطیلات';

    const CREATE_TITLE = 'تقویم تعطیلات - ایجاد';

    const EDIT_TITLE = 'تقویم تعطیلات - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $columns = $this->getColumns();

        return view('freeday::admin.index', compact('title', 'routeData', 'columns'));
    }

    public function data()
    {

        try {
            $freeDays = FreeDay::query();

            return DataTables::eloquent($freeDays)
                ->editColumn('free_at', function (FreeDay $freeDay) {
                    return verta($freeDay->free_at)->format(formatJalaliMonth());
                })
                ->editColumn('created_at', function (FreeDay $freeDay) {
                    return $freeDay->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (FreeDay $freeDay) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.free-day.edit', $freeDay->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('freeday::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            $item = $this->prepareItemData($request);
            FreeDay::create($item);

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(FreeDay $freeDay)
    {
        $title = self::EDIT_TITLE;

        return view('freeday::admin.edit', compact('title', 'freeDay'));
    }

    public function update(UpdateRequest $request, FreeDay $freeDay)
    {
        try {
            $item = $this->prepareItemData($request);
            $freeDay->update($item);

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(FreeDay $freeDay)
    {
        try {
            $freeDay->delete();

            return $this->successDestroyBack(route('admin.free-day.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $freeDayData['title'] = $request->input('title');
        $freeDayData['free_at'] = Helper::toGregorian($request->input('free_at'));

        return $freeDayData;
    }

    public function getDataRoute(): string
    {
        return route('admin.free-day.data');
    }

    public function getColumns(): array
    {
        $columnOption = resolve(ColumnOption::class);

        return [
            $columnOption->setName('id')
                ->setAs('شناسه')
                ->make(),
            $columnOption->clear()
                ->setName('title')
                ->setAs('عنوان')
                ->make(),
            $columnOption->clear()
                ->setName('free_at')
                ->setAs('تاریخ')
                ->make(),
            $columnOption->clear()
                ->setName('action')
                ->setAs('عملیات')
                ->removeAction()
                ->make(),
        ];
    }
}
