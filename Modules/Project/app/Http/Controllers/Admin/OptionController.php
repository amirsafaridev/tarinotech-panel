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
use Modules\Project\app\Http\Requests\Admin\Option\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Option\UpdateRequest;
use Modules\Project\app\Models\ProjectOption;
use Yajra\DataTables\Facades\DataTables;

class OptionController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'امکانات پروژه ها';

    const CREATE_TITLE = 'امکانات پروژه ها - ایجاد';

    const EDIT_TITLE = 'امکانات پروژه ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.option.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.option.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            ProjectOption::create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(ProjectOption $projectOption)
    {
        $title = self::EDIT_TITLE;

        return view('project::admin.option.edit', compact('title', 'projectOption'));
    }

    public function update(UpdateRequest $request, ProjectOption $projectOption)
    {
        try {
            $projectOption->update($this->prepareItemData($request));

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(ProjectOption $projectOption)
    {
        try {
            $projectOption->delete();

            return $this->successDestroyBack(route('admin.project.option.index'));

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
        return route('admin.project.option.data');
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
                ColumnOption::new()->setName('base.title')->setAs('نوع')
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
            $projectOptions = ProjectOption::query()
                ->with('base');

            return DataTables::eloquent($projectOptions)
                ->editColumn('created_at', function ($projectOption) {
                    return $projectOption->created_at->toJalali()->format(formatJalaliDate());
                })
                ->addColumn('action', function ($projectOption) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.project.option.edit', $projectOption->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
