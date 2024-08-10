<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\Database\Role\PermissionName;
use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\AdminFilter;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Filters\TypeFilter;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectWeb;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پروژه ها';

    const SHOW_TITLE = 'نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.index', compact('title', 'routeData', 'dataTable'));
    }

    public function manage(Project $project)
    {

        $title = self::SHOW_TITLE.' - '.$project->title;

        $project->load([
            'admin',
            'user',
            'status',
            'type',
            'businessDomain',
            'factors.admin',
            'target.signable',
            'target.userSignable.attachments',
        ]);

        if ($project->target_type === ProjectWeb::class) {
            $project->load('target.options');
        }

        return view('project::admin.show', compact('title', 'project'));
    }

    public function getDataRoute(): string
    {
        return route('admin.project.data');
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
                    ->setName('admin.fullname')
                    ->setSearchable(false)
                    ->setSortable(false)
                    ->setAs('نام کارشناس فروش')
            )
            ->addColumn(
                ColumnOption::new()->setName('type.title')
                    ->setSortable(false)
                    ->setAs('نوع')
            )
            ->addColumn(
                ColumnOption::new()->setName('status.title')
                    ->setSortable(false)
                    ->setAs('وضعیت')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('price')
                    ->setAs('قیمت')
                    ->setVisible(
                        hasAdminPermission(PermissionName::PROJECT_PRICE_SHOW)
                    )
            )
            ->addColumn(
                ColumnOption::new()->setName('domain')->setAs('دامنه')
            )
            ->addColumn(
                ColumnOption::new()->setName('created_at')->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->addExternalFilter(ExternalFilter::new()->setKey('admin'))
            ->addExternalFilter(ExternalFilter::new()->setKey('type'))
            ->addExternalFilter(ExternalFilter::new()->setKey('status'))
            ->render();
    }

    public function data()
    {
        try {

            $projects = Project::query()
                ->select([
                    'id',
                    'title',
                    'domain',
                    'type_id',
                    'status_id',
                    'admin_id',
                    'created_at',
                ])
                ->filter([
                    AdminFilter::class,
                    TypeFilter::class,
                    StatusFilter::class,
                ])
                ->with([
                    'type.base',
                    'status.type',
                    'admin' => function (BelongsTo $query) {
                        $query->select('admins.id', 'admins.first_name', 'admins.last_name');
                    },
                ]);

            if (hasAdminPermission(PermissionName::PROJECT_PRICE_SHOW)) {
                $projects->addSelect('price');
            }

            return DataTables::eloquent($projects)
                ->editColumn('created_at', function (Project $project) {
                    return $project->created_at->toJalali()->format('Y/m/d');
                })
                ->editColumn('price', function (Project $project) {
                    return number_format($project->price);
                })
                ->addColumn('action', function ($project) {
                    return Helper::btnMaker(BtnType::Success, route('admin.project.manage', $project->id), trans('panel.action.manage'));
                })
                ->rawColumns(['action'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
