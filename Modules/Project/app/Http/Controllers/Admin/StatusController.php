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
use Modules\Project\app\Http\Requests\Admin\Status\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Status\UpdateRequest;
use Modules\Project\app\Models\ProjectStatus;
use Yajra\DataTables\Facades\DataTables;

class StatusController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'وضعیت پروژه ها';

    const CREATE_TITLE = 'وضعیت پروژه ها - ایجاد';

    const EDIT_TITLE = 'وضعیت پروژه ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.status.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.status.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            ProjectStatus::create($this->prepareItemData($request));

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function edit(ProjectStatus $projectStatus)
    {
        $title = self::EDIT_TITLE;

        return view('project::admin.status.edit', compact('title', 'projectStatus'));
    }

    public function update(UpdateRequest $request, ProjectStatus $projectStatus)
    {
        try {

            $projectStatus->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(ProjectStatus $projectStatus)
    {
        try {
            $projectStatus->delete();

            return $this->successDestroyBack(route('admin.project.status.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['type_id'] = $request->input('type_id');
        $item['note'] = $request->input('note');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.project.status.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('type.title')->setAs('نوع')
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
            $projectStatus = ProjectStatus::query()
                ->select('*')
                ->with('type');

            return DataTables::eloquent($projectStatus)
                ->editColumn('created_at', function (ProjectStatus $projectStatus) {
                    return $projectStatus->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (ProjectStatus $projectStatus) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.project.status.edit', $projectStatus->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
