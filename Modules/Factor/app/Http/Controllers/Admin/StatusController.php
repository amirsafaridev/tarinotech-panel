<?php

namespace Modules\Factor\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Http\Requests\Admin\FactorStatus\StoreRequest;
use Modules\Factor\app\Http\Requests\Admin\FactorStatus\UpdateRequest;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorStatusForward;
use Yajra\DataTables\Facades\DataTables;

class StatusController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'وضعیت خودکار';

    const CREATE_TITLE = 'وضعیت خودکار - ایجاد';

    const EDIT_TITLE = 'وضعیت خودکار - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('factor::admin.status.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('factor::admin.status.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {

            FactorStatusForward::query()
                ->create($this->prepareItemData($request));

            return $this->successResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(FactorStatusForward $factorStatusForward)
    {

        $title = self::EDIT_TITLE;

        $factorStatusForward->load('currentStatus.type');

        return view('factor::admin.status.edit', compact('title', 'factorStatusForward'));
    }

    public function update(UpdateRequest $request, FactorStatusForward $factorStatusForward)
    {
        try {

            $factorStatusForward->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Factor $factorStatus)
    {
        try {
            $factorStatus->delete();

            return $this->successDestroyBack(route('admin.factor.status.index'));

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionBack($exception);

        }
    }

    protected function prepareItemData(Request $request): array
    {
        $factorStatusData['factor_status'] = $request->input('factor_status');
        $factorStatusData['project_status_id'] = $request->input('project_status_id');
        $factorStatusData['project_status_forward_id'] = $request->input('project_status_forward_id');

        return $factorStatusData;
    }

    public function getDataRoute(): string
    {
        return route('admin.factor.status.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('factor_status')
                    ->setAs('عنوان')
            )
            ->addColumn(
                ColumnOption::new()->setName('current_status.title')
                    ->setAs('وضعیت جاری')
            )
            ->addColumn(
                ColumnOption::new()->setName('forward_status.title')
                    ->setAs('به وضعیت')
            )

            ->addColumn(
                ColumnOption::new()->setName('created_at')->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->render();
    }

    public function data()
    {
        try {
            $factorStatuses = FactorStatusForward::query()
                ->select([
                    'id',
                    'factor_status',
                    'project_status_id',
                    'project_status_forward_id',
                    'created_at',
                ])
                ->with(['currentStatus', 'forwardStatus']);

            return DataTables::eloquent($factorStatuses)
                ->editColumn('factor_status', function (FactorStatusForward $factorStatus) {
                    return factorStatusRender($factorStatus->factor_status);
                })
                ->editColumn('created_at', function (FactorStatusForward $factorStatus) {
                    return $factorStatus->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (FactorStatusForward $factorStatus) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.factor.status.edit', $factorStatus->id), trans('panel.action.edit'));
                })
                ->rawColumns(['action', 'factor_status'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
