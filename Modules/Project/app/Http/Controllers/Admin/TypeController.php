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
use Modules\Project\app\Http\Requests\Admin\Type\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Type\UpdateRequest;
use Modules\Project\app\Models\ProjectType;
use Yajra\DataTables\Facades\DataTables;

class TypeController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'نوع پروژه ها';

    const CREATE_TITLE = 'نوع پروژه ها - ایجاد';

    const EDIT_TITLE = 'نوع پروژه ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.type.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.type.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            ProjectType::create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(ProjectType $projectType)
    {
        $title = self::EDIT_TITLE;

        return view('project::admin.type.edit', compact('title', 'projectType'));
    }

    public function update(UpdateRequest $request, ProjectType $projectType)
    {
        try {
            $projectType->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(ProjectType $projectType)
    {
        try {
            $projectType->delete();

            return $this->successDestroyBack(route('admin.project.type.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['base_id'] = $request->input('base_id');
        $item['note'] = $request->input('note');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.project.type.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('base.title')->setAs('نوع')
            )
            ->addColumn(
                ColumnOption::new()->setName('title')->setAs('عنوان')
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
            $projectTypes = ProjectType::query()
                ->with('base');

            return DataTables::eloquent($projectTypes)
                ->editColumn('created_at', function ($projectType) {
                    return $projectType->created_at->toJalali()->format(formatJalaliDate());
                })
                ->addColumn('action', function ($projectType) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.project.type.edit', $projectType->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
