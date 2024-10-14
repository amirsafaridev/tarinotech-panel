<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\Database\Role\PermissionName;
use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\AdminFilter;
use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Project\app\Exports\Admin\Report\ProjectDatatableExport;
use Modules\Project\app\Filters\IsSignFilter;
use Modules\Project\app\Filters\IsUserSignFilter;
use Modules\Project\app\Filters\Project\DateFilter;
use Modules\Project\app\Filters\Project\SortFilter;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Filters\TypeFilter;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectWeb;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    const INDEX_TITLE = 'پروژه ها';

    const SHOW_TITLE = 'نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $selectedColumns = collect([
            'projects.id',
            'projects.title',
            'projects.admin_id',
            'projects.price',
            'projects.domain',
            'projects.created_at',
            'projects.is_signed',
            'projects.is_signed_user',
            'admins.first_name as admin_first_name',
            'admins.last_name as admin_last_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
            'project_types.title as project_types_title',
            'project_bases.title as project_bases_title',
            'project_statuses.title as project_statuses_title',
        ]);

        $hasPricePermission = hasAdminPermission(PermissionName::PROJECT_PRICE_SHOW);
        if ($hasPricePermission) {
            $selectedColumns->add('projects.price');
        }

        $projects = Project::query()
            ->select($selectedColumns->toArray())
            ->join('admins', 'projects.admin_id', '=', 'admins.id')
            ->join('users', 'projects.user_id', '=', 'users.id')
            ->join('project_types', 'projects.type_id', '=', 'project_types.id')
            ->join('project_bases', 'projects.base_id', '=', 'project_bases.id')
            ->join('project_statuses', 'projects.status_id', '=', 'project_statuses.id')
            ->filter([
                AdminJoinedFilter::class,
                TypeFilter::class,
                StatusFilter::class,
                IsSignFilter::class,
                IsUserSignFilter::class,
                DateFilter::class,
                SortFilter::class,
            ]);

        if (request('export')) {
            return $this->export($projects->get());
        }

        $projects = $projects->paginate();

        return view('project::admin.index', compact('title', 'projects', 'hasPricePermission'));
    }

    private function export($factors)
    {
        try {
            $fileName = 'Project-'.Carbon::now()->format('Y-m-d').'.xlsx';

            return Excel::download(new ProjectDatatableExport(collect($factors)), $fileName);
        } catch (Exception $exception) {
            report($exception);

            return back()->with('danger', 'خطا در هنگام صادر کردن اطلاعات');
        }
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
            ->addExternalFilter(ExternalFilter::new()->setKey('is_signed'))
            ->addExternalFilter(ExternalFilter::new()->setKey('is_signed_user'))
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
                    IsSignFilter::class,
                    IsUserSignFilter::class,
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
