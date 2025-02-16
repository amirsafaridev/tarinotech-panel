<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\app\Http\Requests\Admin\JobTitle\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\JobTitle\UpdateRequest;
use Modules\Admin\app\Models\JobTitle;
use Yajra\DataTables\Facades\DataTables;

class JobTitleController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'عنوان شغلی';

    const CREATE_TITLE = 'عنوان شغلی - ایجاد';

    const EDIT_TITLE = 'عنوان شغلی - ویرایش';

    const SHOW_TITLE = 'عنوان شغلی - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('admin::admin.job-title.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.job-title.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {

            JobTitle::query()->create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(JobTitle $jobTitle)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.job-title.edit', compact('title', 'jobTitle'));
    }

    public function update(UpdateRequest $request, JobTitle $jobTitle)
    {
        try {

            $jobTitle->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(JobTitle $jobTitle)
    {
        try {
            $jobTitle->delete();

            return $this->successDestroyBack(route('admin.admin.job-title.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);

        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $request): array
    {
        $jobTitleData['title'] = $request->input('title');

        return $jobTitleData;
    }

    public function getDataRoute(): string
    {
        return route('admin.admin.job-title.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(ColumnOption::new()->setName('id')->setAs('شناسه'))
            ->addColumn(ColumnOption::new()->setName('title')->setAs('عنوان'))
            ->addColumn(ColumnOption::new()
                ->setName('admins_count')
                ->setSearchable(false)
                ->setAs('تعداد پرسنل'))
            ->addColumn(ColumnOption::new()->setName('created_at')->setAs('تاریخ ایجاد'))
            ->addColumn(ColumnOption::new()->setName('action')->setAs('عملیات')->removeAction())
            ->render();
    }

    public function data()
    {
        try {
            $positions = JobTitle::query()
                ->select('*')
                ->withCount('admins');

            return DataTables::eloquent($positions)
                ->editColumn('created_at', function (JobTitle $jobTitle) {
                    return $jobTitle->created_at->toJalali()->format(formatJalaliDate());
                })

                ->addColumn('action', function (JobTitle $jobTitle) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.admin.job-title.edit', $jobTitle->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
