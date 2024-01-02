<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\AdminFilter;
use App\Filters\Admin\Package\PackageID;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Service\Json\WebProject\DomainTransformer;
use App\Service\Json\WebProject\HostTransformer;
use App\Service\Json\WebProject\LanguageTransformer;
use App\Service\Json\WebProject\SampleTransformer;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Carbon\Carbon;
use Crypt;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Filters\TypeFilter;
use Modules\Project\app\Http\Requests\Admin\Web\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Web\UpdateRequest;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectWeb;
use Yajra\DataTables\Facades\DataTables;

class WebController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پروژه های وب';

    const CREATE_TITLE = 'پروژه های وب - ایجاد';

    const EDIT_TITLE = 'پروژه های وب - ویرایش';

    const SHOW_TITLE = 'پروژه های وب - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('project::admin.web.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('project::admin.web.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $projectWeb = ProjectWeb::query()->create($this->initialWebData($request));

            $projectParams = $this->initialProjectData($request);
            $agreementAt = $projectParams['agreement_at'];
            if ($agreementAt) {
                $projectParams['renewal_at'] = Carbon::parse($agreementAt)->addYear()
                    ->format('Y-m-d');
            }
            $projectWeb->project()->create($projectParams);
            $projectWeb->options()->attach($request->input('options'));

            DB::commit();

            return $this->successResponse();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit($projectId)
    {
        $project = $this->getOrFailProject($projectId);

        $title = self::EDIT_TITLE;

        return view('project::admin.web.edit', compact('title', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = $this->getOrFailProject($projectId);

            DB::beginTransaction();
            $project->update($this->initialProjectData($request));

            /**
             * @var $projectWeb ProjectWeb
             */
            $projectWeb = $project->target;
            $projectWeb->update($this->initialWebData($request));
            $projectWeb->options()->attach($request->input('options'));
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show($projectId)
    {
        $project = $this->getOrFailProject($projectId);

        $title = self::SHOW_TITLE;

        return view('project::admin.web.show', compact('title', 'project'));
    }

    public function destroy($projectId)
    {
        try {
            $project = $this->getOrFailProject($projectId);
            $project->delete();

            return $this->successDestroyBack(route('admin.project.web.index'));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionBack($exception);
        }
    }

    private function getDomain(Request $req): DomainTransformer
    {
        $domainPassword = $req->input('domain_password') ? Crypt::encrypt($req->input('domain_password')) : '';
        $domains = resolve(DomainTransformer::class);
        $domains->setHaveDomain($req->has('have_domain'));
        $domains->setDomainProviderWebsite($req->input('domain_provider_website'));
        $domains->setDomainUsername($req->input('domain_username'));
        $domains->setDomainPassword($domainPassword);
        $domains->setDomainPrimary($req->input('domain_primary'));
        $domains->setDomainsRequired($req->input('domains_required', []));
        $domains->setOtherDomain($req->input('other_domain'));

        return $domains;
    }

    private function getHost(Request $req): HostTransformer
    {
        $hostPassword = $req->input('host_password') ? Crypt::encrypt($req->input('host_password')) : '';

        $host = resolve(HostTransformer::class);
        $host->setHaveHost($req->has('have_host'));
        $host->setHostProvider($req->input('host_provider'));
        $host->setHostUsername($req->input('host_username'));
        $host->setHostPassword($hostPassword);
        $host->setHostLocation($req->input('host_location'));
        $host->setHostMostVisit($req->has('host_most_visit'));

        return $host;
    }

    private function getLanguage(Request $req): LanguageTransformer
    {

        $language = resolve(LanguageTransformer::class);
        $language->setPrimaryLanguage($req->input('primary_language'));
        $language->setLanguages($req->input('languages', []));

        return $language;
    }

    private function getSample(Request $req): SampleTransformer
    {
        $favoriteSites = [];
        if ($req->input('favorite_sites')) {
            $favoriteSites = explode(PHP_EOL, $req->input('favorite_sites'));
        }

        $similarSites = [];
        if ($req->input('similar_sites')) {
            $similarSites = explode(PHP_EOL, $req->input('similar_sites'));
        }
        $language = resolve(SampleTransformer::class);
        $language->setFavoriteSites($favoriteSites);
        $language->setSimilarSites($similarSites);

        return $language;
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
            'base_id' => ProjectBase::Web,
            'price' => $request->input('price'),
            'type_id' => $request->input('type_id'),
            'note' => $request->input('note'),
            'business_domain_id' => $request->input('business_domain_id'),
            'business_domain' => $request->input('business_domain'),
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

    private function initialWebData(Request $request): array
    {
        $domain = $this->getDomain($request);
        $host = $this->getHost($request);
        $language = $this->getLanguage($request);
        $sample = $this->getSample($request);

        return [
            'field_activity' => $request->input('field_activity'),
            'package_id' => $request->input('package_id'),
            'pages' => $request->input('pages'),
            'domains' => $domain->toArray(),
            'host' => $host->toArray(),
            'language' => $language->toArray(),
            'sample' => $sample->toArray(),
            'working_days' => $request->input('working_days'),
        ];
    }

    private function getOrFailProject($projectId): Project
    {
        return Project::query()
            ->whereHasMorph('target', [ProjectWeb::class])
            ->with('target.options')
            ->findOrFail($projectId);
    }

    public function getDataRoute(): string
    {
        return route('admin.project.web.data');
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
                    ->setAs('نام کارشناس')
            )
            ->addColumn(
                ColumnOption::new()->setName('target.package.title')
                    ->setSortable(false)
                    ->setAs('نوع')
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
                ->whereHasMorph('target', [ProjectWeb::class], function ($q) {
                    $q->filter([
                        PackageID::class,
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

            return DataTables::eloquent($projects)
                ->editColumn('created_at', function (Project $project) {
                    return $project->created_at->toJalali()->format('Y/m/d');
                })
                ->editColumn('price', function (Project $project) {
                    return number_format($project->price);
                })
                ->addColumn('action', function ($project) {
                    $actions = Helper::btnMaker(BtnType::Warning, route('admin.project.web.edit', $project->id), trans('panel.action.edit'));
                    $actions .= Helper::btnMaker(BtnType::Info, route('admin.project.web.show', $project->id), trans('panel.action.show'));

                    return $actions;
                })
                ->rawColumns(['action'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
