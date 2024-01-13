<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Project\app\Http\Requests\Admin\Facility\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Facility\UpdateRequest;
use Modules\Project\app\Models\Facility;
use Yajra\DataTables\Facades\DataTables;

use function route;
use function trans;
use function view;

class FacilityController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'امکانات جانبی';

    const CREATE_TITLE = 'امکانات جانبی - ایجاد';

    const EDIT_TITLE = 'امکانات جانبی - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.facility.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.facility.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            Facility::query()->create($this->prepareItemData($request));

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Facility $facility)
    {
        $title = self::EDIT_TITLE;

        return view('project::admin.facility.edit', compact('title', 'facility'));
    }

    public function update(UpdateRequest $request, Facility $facility)
    {
        try {
            $facility->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Facility $facility)
    {
        try {
            $facility->delete();

            return $this->successDestroyBack(route('admin.project.facility.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['base_id'] = $request->input('base_id');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.project.facility.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('title')->setAs('عنوان')
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
            $facilities = Facility::query();

            return DataTables::eloquent($facilities)
                ->editColumn('created_at', function (Facility $facility) {
                    return $facility->created_at->toJalali()->format(formatJalaliDate());
                })
                ->addColumn('action', function (Facility $facility) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.project.facility.edit', $facility->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
