<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\AdminFilter;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Filters\TypeFilter;
use Modules\Project\app\Http\Requests\Admin\Ads\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Ads\UpdateRequest;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectAds;
use Yajra\DataTables\Facades\DataTables;

class AdsController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پروژه های ادز';

    const CREATE_TITLE = 'پروژه های ادز - ایجاد';

    const EDIT_TITLE = 'پروژه های ادز - ویرایش';

    const SHOW_TITLE = 'پروژه های ادز - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.ads.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.ads.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $projectData = $this->initialAdsData($request);
            $projectAds = ProjectAds::query()->create($projectData);

            $projectParams = $this->initialProjectData($request);
            $projectParams['tax_rate'] = config('factor.tax');

            $projectAds->project()->create($projectParams);
            DB::commit();

            return $this->successResponse(
                route('admin.project.ads.index')
            );
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit($projectId)
    {
        $project = Project::findAdsTarget($projectId);

        $title = self::EDIT_TITLE;

        return view('project::admin.ads.edit', compact('title', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = Project::findAdsTarget($projectId);

            DB::beginTransaction();
            $project->update($this->initialProjectData($request));
            $project->target->update($this->initialAdsData($request));
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy($projectId)
    {
        try {
            $project = Project::findAdsTarget($projectId);
            $project->delete();

            return $this->successDestroyBack(route('admin.project.ads.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    private function initialProjectData(Request $request): array
    {
        $agreementAt = $request->input('agreement_at');
        $deadlineAt = $request->input('deadline_at');

        $data = [
            'title' => $request->input('title'),
            'domain' => $request->input('domain_primary'),
            'admin_id' => auth()->id(),
            'user_id' => $request->input('user_id'),
            'status_id' => $request->input('status_id'),
            'base_id' => ProjectBase::Ads,
            'price' => $request->input('price', 0),
            'type_id' => $request->input('type_id'),
            'note' => $request->input('note'),
            'contract_attachment' => $request->input('contract_attachment'),
        ];

        if (! empty($agreementAt)) {
            $data['agreement_at'] = Helper::toGregorian($agreementAt);
        } else {
            $data['agreement_at'] = null;
        }

        if (! empty($deadlineAt)) {
            $data['deadline_at'] = Helper::toGregorian($deadlineAt);
        } else {
            $data['deadline_at'] = null;
        }

        return $data;
    }

    private function initialAdsData(Request $req): array
    {
        return [
            'field_activity' => $req->input('field_activity'),
            'designed_by' => $req->input('designed_by'),
        ];
    }

    public function getDataRoute(): string
    {
        return route('admin.project.ads.data');
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
                    'target_type',
                    'target_id',
                    'admin_id',
                    'created_at',
                ])
                ->whereHasMorph('target', [ProjectAds::class])
                ->with([
                    'type.base',
                    'status.type',
                    'target',
                    'admin' => function (BelongsTo $query) {
                        $query->select('admins.id', 'admins.first_name', 'admins.last_name');
                    },
                ])
                ->filter([
                    AdminFilter::class,
                    TypeFilter::class,
                    StatusFilter::class,
                ]);

            return DataTables::eloquent($projects)
                ->editColumn('created_at', function (Project $project) {
                    return $project->created_at->toJalali()->format('Y/m/d');
                })
                ->addColumn('action', function ($project) {
                    $actions = Helper::btnMaker(BtnType::Warning, route('admin.project.ads.edit', $project->id), trans('panel.action.edit'));
                    $actions .= Helper::btnMaker(BtnType::Info, route('admin.project.manage', $project->id), trans('panel.action.show'));

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
