<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Domain\Jobs\SeoProjectFactorMakeJob;
use App\Enums\Database\Role\PermissionName;
use App\Enums\General\DropdownItemColor;
use App\Filters\Admin\Admin\AdminFilter;
use App\Filters\Admin\Package\PackageID;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\Dropdown;
use App\Foundation\ValueObjects\Datatable\DropdownItem;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Service\Json\SeoProject\HostTransformer;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Filters\TypeFilter;
use Modules\Project\app\Http\Requests\Admin\Seo\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Seo\UpdateRequest;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectSeo;
use Yajra\DataTables\Facades\DataTables;

class SeoController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پروژه های سئو';

    const CREATE_TITLE = 'پروژه های سئو - ایجاد';

    const EDIT_TITLE = 'ویرایش';

    const SHOW_TITLE = 'نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.seo.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.seo.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $projectData = $this->initialSeoData($request);
            $projectSeo = ProjectSeo::query()->create($projectData);

            $projectParams = $this->initialProjectData($request);
            $projectParams['tax_rate'] = config('factor.tax');

            $projectSeo->project()->create($projectParams);

            $seoFactorMakeJob = resolve(SeoProjectFactorMakeJob::class);
            $seoFactorMakeJob->handle($projectSeo);

            DB::commit();

            return $this->successResponse(
                route('admin.project.seo.index')
            );
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit($projectId)
    {
        $project = Project::findSeoTarget($projectId);

        $title = self::EDIT_TITLE.' - '.$project->title;

        return view('project::admin.seo.edit', compact('title', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = Project::findSeoTarget($projectId);

            DB::beginTransaction();
            $project->update($this->initialProjectData($request));
            $project->target->update($this->initialSeoData($request));
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
            $project = Project::findSeoTarget($projectId);
            $project->delete();

            return $this->successDestroyBack(route('admin.project.seo.index'));
        } catch (Exception $exception) {
            DB::rollBack();

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
            'base_id' => ProjectBase::Seo,
            'price' => $request->input('price'),
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

    private function initialSeoData(Request $req): array
    {

        $host = $this->getHost($req);

        return [
            'field_activity' => $req->input('field_activity'),
            'host' => $host->toArray(),
            'agreement_duration' => $req->input('agreement_duration'),
            'amount_content' => $req->input('amount_content'),
            'keywords_count' => $req->input('keywords_count'),
            'keywords' => $req->input('keywords'),
            'price_monthly' => $req->input('price_monthly'),
            'due_date_payments' => $req->input('due_date_payments'),
            'designed_by' => $req->input('designed_by'),
            'package_id' => $req->input('package_id'),
        ];
    }

    private function getHost(Request $req): HostTransformer
    {
        $host = resolve(HostTransformer::class);
        $host->setHostLocation($req->input('host_location'));
        $host->setHostProvider($req->input('host_provider'));

        return $host;
    }

    public function getDataRoute(): string
    {
        return route('admin.project.seo.data');
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
                ColumnOption::new()->setName('target.package.title')
                    ->setSearchable(false)
                    ->setSortable(false)
                    ->setAs('پکیج')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('admin.fullname')
                    ->setSearchable(false)
                    ->setSortable(false)
                    ->setAs('نام کارشناس فروش')
            )
            ->addColumn(
                ColumnOption::new()->setName('target.price_monthly')
                    ->setSortable(false)
                    ->setAs('پرداخت ماهیانه')
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
            ->addExternalFilter(ExternalFilter::new()->setKey('package_id'))
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
                    'target_type',
                    'target_id',
                    'admin_id',
                    'created_at',
                ])
                ->whereHasMorph('target', [ProjectSeo::class], function ($q) {
                    $q->filter([
                        PackageID::class,
                        StatusFilter::class,
                    ]);
                })
                ->with([
                    'type.base',
                    'status.type',
                    'target.package',
                    'admin' => function (BelongsTo $query) {
                        $query->select('admins.id', 'admins.first_name', 'admins.last_name');
                    },
                ])
                ->filter([
                    AdminFilter::class,
                    TypeFilter::class,
                    StatusFilter::class,
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
                ->editColumn('target.package.title', function (Project $project) {
                    return $project->target?->package?->title ?? '-';
                })
                ->editColumn('target.price_monthly', function (Project $project) {
                    return number_format($project->target->price_monthly);
                })
                ->addColumn('action', function ($project) {

                    return (new Dropdown())
                        ->add(
                            (new DropdownItem())
                                ->setTargetBlank(true)
                                ->setTitle(trans('panel.action.edit'))
                                ->setLink(route('admin.project.seo.edit', $project->id))
                        )
                        ->add(
                            (new DropdownItem())
                                ->setTargetBlank(true)
                                ->setTitle(trans('panel.action.show'))
                                ->setLink(route('admin.project.manage', $project->id))
                        )
                        ->add(
                            (new DropdownItem())
                                ->setTargetBlank(true)
                                ->setTitle(trans('panel.action.auto_factor'))
                                ->setLink(route('admin.project.seo.auto-factor', $project->id))
                        )
                        ->setButtonColor(DropdownItemColor::Success())
                        ->render();

                })
                ->rawColumns(['action'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
