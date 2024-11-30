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
use Modules\Project\app\Models\Project;
use Yajra\DataTables\Facades\DataTables;

class ProjectRenewalController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'لیست تمدیدها';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.index_renewal', compact('title', 'routeData', 'dataTable'));
    }

    public function getDataRoute(): string
    {
        return route('admin.project.renewal.data');
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
                ColumnOption::new()->setName('domain')->setAs('دامنه')
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
                ColumnOption::new()->setName('price')->setAs('قیمت')
            )
            ->addColumn(
                ColumnOption::new()->setName('domain')->setAs('دامنه')
            )
            ->addColumn(
                ColumnOption::new()->setName('agreement_at')->setAs('قرارداد')
            )
            ->addColumn(
                ColumnOption::new()->setName('renewal_at')->setAs('تمدید')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            /*->addExternalFilter(ExternalFilter::new()->setKey('admin'))
            ->addExternalFilter(ExternalFilter::new()->setKey('type'))
            ->addExternalFilter(ExternalFilter::new()->setKey('status'))*/
            ->render();
    }

    public function data()
    {
        try {
            $projects = Project::query()
                ->select([
                    'id',
                    'title',
                    'price',
                    'domain',
                    'type_id',
                    'status_id',
                    'renewal_at',
                    'agreement_at',
                ])
                /*->filter([
                    AdminFilter::class,
                    TypeFilter::class,
                    StatusFilter::class,
                ])*/
                ->with([
                    'type.base',
                    'status.type',
                    /*'admin' => function (BelongsTo $query) {
                        $query->select('admins.id', 'admins.first_name', 'admins.last_name');
                    },*/
                ])
                ->whereNotNull('renewal_at');

            return DataTables::eloquent($projects)
                ->editColumn('agreement_at', function (Project $project) {
                    return $project->agreement_at->toJalali()->format(formatJalaliDate());
                })
                ->editColumn('renewal_at', function (Project $project) {
                    return $project->renewal_at->toJalali()->format(formatJalaliDate());
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
