<?php

namespace Modules\Contract\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Contract\app\Http\Requests\Admin\Signable\UpdateRequest;
use Modules\Contract\app\Models\Signable;
use Yajra\DataTables\Facades\DataTables;

class SignController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'درخواست های امضاء';

    const EDIT_TITLE = 'درخواست های امضاء - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('contract::admin.signable.index', compact('title', 'routeData', 'dataTable'));

    }

    public function edit(Signable $signable)
    {
        $title = self::EDIT_TITLE;

        return view('contract::admin.signable.edit', compact('title', 'signable'));
    }

    public function update(UpdateRequest $request, Signable $signable)
    {
        try {
            $item = $this->prepareItemData($request);
            $signable->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Signable $signable)
    {
        try {
            $signable->delete();

            return $this->successDestroyBack(route('admin.contract.sign.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $req): array
    {
        $signableData['status'] = $req->input('status');
        $signableData['note'] = $req->input('note');
        $signableData['sign_at'] = now();

        return $signableData;
    }

    public function getDataRoute(): string
    {
        return route('admin.contract.sign.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('make_admin.fullname')->setAs('درخواست کننده')
            )
            ->addColumn(
                ColumnOption::new()->setName('target.project.domain')->setAs('پروژه')
            )
            ->addColumn(
                ColumnOption::new()->setName('status')->setAs('وضعیت')
            )
            ->addColumn(
                ColumnOption::new()->setName('sign_at')->setAs('تاریخ امضاء')
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
            $signables = Signable::query()
                ->with(['target.project', 'makeAdmin']);

            return DataTables::eloquent($signables)
                ->editColumn('status', function ($signable) {
                    return Helper::renderSignableStatus($signable->status);
                })
                ->editColumn('sign_at', function ($signable) {
                    return $signable->sign_at ? $signable->sign_at->toJalali()->format(formatJalaliDateTime()) : '-';
                })
                ->editColumn('created_at', function ($signable) {
                    return $signable->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (Signable $signable) {
                    $actions = Helper::btnMaker(BtnType::Info, makeRouteContractPreview($signable->target_type, $signable->target_id), trans('panel.action.printContract'));
                    $actions .= Helper::btnMaker(BtnType::Warning, route('admin.contract.sign.edit', $signable->id), trans('panel.action.edit'));

                    return $actions;
                })
                ->rawColumns(['action', 'status'])
                ->make();

        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
