<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Modules\Project\app\Http\Requests\Admin\BusinessDomain\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\BusinessDomain\UpdateRequest;
use Modules\Project\app\Models\BusinessDomain;
use Yajra\DataTables\Facades\DataTables;

class BusinessDomainController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'زمینه کاری';

    const CREATE_TITLE = 'زمینه کاری - ایجاد';

    const EDIT_TITLE = 'زمینه کاری - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.business_domain.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.business_domain.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            BusinessDomain::query()->create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(BusinessDomain $businessDomain)
    {
        $title = self::EDIT_TITLE;

        return view('project::admin.business_domain.edit', compact('title', 'businessDomain'));
    }

    public function update(UpdateRequest $request, BusinessDomain $businessDomain)
    {
        try {
            $businessDomain->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(BusinessDomain $businessDomain)
    {
        try {
            $businessDomain->delete();

            return $this->successDestroyBack(route('admin.project.business_domain.index'));

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
        return route('admin.project.business_domain.data');
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
                ColumnOption::new()
                    ->setName('projects_count')
                    ->setAs('تعداد پروژه')
                    ->setSearchable(false)
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
            $businessDomains = BusinessDomain::query()
                ->withCount('projects');

            return DataTables::eloquent($businessDomains)
                ->editColumn('created_at', function ($businessDomain) {
                    return $businessDomain->created_at->toJalali()->format(formatJalaliDate());
                })
                ->addColumn('action', function ($businessDomain) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.project.business_domain.edit', $businessDomain->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
