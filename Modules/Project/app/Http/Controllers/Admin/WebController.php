<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\Database\Role\PermissionName;
use App\Enums\Database\Role\RoleName;
use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Service\Json\WebProject\DomainTransformer;
use App\Service\Json\WebProject\HostTransformer;
use App\Service\Json\WebProject\LanguageTransformer;
use App\Service\Json\WebProject\SampleTransformer;
use App\Traits\HasJsonCommonResponseTrait;
use Carbon\Carbon;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Filters\IsSignFilter;
use Modules\Project\app\Filters\IsUserSignFilter;
use Modules\Project\app\Filters\Project\DateFilter;
use Modules\Project\app\Filters\Project\SearchFilter;
use Modules\Project\app\Filters\Project\SortFilter;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Filters\Web\PackageFilter;
use Modules\Project\app\Http\Requests\Admin\Web\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Web\UpdateRequest;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectFacility;
use Modules\Project\app\Models\ProjectWeb;

class WebController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'پروژه های وب';

    const CREATE_TITLE = 'پروژه های وب - ایجاد';

    const EDIT_TITLE = 'ویرایش';

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
            'projects.target_id',
            'admins.first_name as admin_first_name',
            'admins.last_name as admin_last_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
            'project_types.title as project_types_title',
            'project_bases.title as project_bases_title',
            'project_statuses.title as project_statuses_title',
            'packages.title as packages_title',
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
            ->join('project_webs', function ($join) {
                $join->on('projects.target_id', '=', 'project_webs.id')
                    ->where('projects.target_type', '=', ProjectWeb::class);
            })
            ->join('packages', 'project_webs.package_id', '=', 'packages.id')
            ->filter([
                SearchFilter::class,
                AdminJoinedFilter::class,
                StatusFilter::class,
                IsSignFilter::class,
                IsUserSignFilter::class,
                DateFilter::class,
                PackageFilter::class,
                SortFilter::class,
            ]);

        $projects = $projects->paginate()
            ->withQueryString();

        return view('project::admin.web.index', compact('title', 'projects', 'hasPricePermission'));
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

            $projectData = $this->initialWebData($request);
            $projectWeb = ProjectWeb::query()->create($projectData);
            $projectParams = $this->initialProjectData($request);
            $projectParams['status_id'] = $request->input('status_id');
            $projectParams['price'] = $request->input('price');
            $projectParams['tax_rate'] = config('factor.tax');
            $projectParams['contract_attachment'] = $request->input('contract_attachment');
            
            if (hasAdminRole(RoleName::SUPER_ADMIN)) {
                $projectParams['admin_id'] = $request->input('admin_id');
            } else {
                $projectParams['admin_id'] = auth()->id();
            }

            $agreementAt = $projectParams['agreement_at'];
            if ($agreementAt) {
                $projectParams['renewal_at'] = Carbon::parse($agreementAt)->addYear()
                    ->format('Y-m-d');
            }
            $project = $projectWeb->project()->create($projectParams);

            $facilities = collect($request->input('facilities'))->mapWithKeys(function ($facilityId) use($projectParams){
                return [
                    $facilityId => [
                        'renewal_at' => now(),
                        'user_id'=>$projectParams['admin_id']
                    ],
                ];
            });

            $project->facilities()->sync($facilities);

            DB::commit();

            return $this->successResponse(
                route('admin.project.web.index')
            );

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit($projectId)
    {
        $project = Project::findWebTarget($projectId);

        $title = self::EDIT_TITLE.' - '.$project->title;

        return view('project::admin.web.edit', compact('title', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = Project::findWebTarget($projectId);

            DB::beginTransaction();

            $projectParams = $this->initialProjectData($request);

            if (hasAdminPermission(PermissionName::PROJECT_PRICE_EDIT)) {
                $projectParams['price'] = $request->input('price');
            }

            if (hasAdminRole(RoleName::SUPER_ADMIN)) {
                $projectParams['admin_id'] = $request->input('admin_id');
            }

            if (! $project->is_signed) {
                $projectParams['contract_attachment'] = $request->input('contract_attachment');
            }
            $project->update($projectParams);

            /**
             * @var $projectWeb ProjectWeb
             */
            $projectWeb = $project->target;
            $projectWeb->update($this->initialWebData($request));

            $facilities = $request->input('facilities');
            if (is_array($facilities) && ! empty($facilities)) {
                $project->syncFacilitiesPreserveRenewal($facilities);
            }

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
            $project = Project::findWebTarget($projectId);
            $project->delete();

            return $this->successDestroyBack(route('admin.project.web.index'));
        } catch (Exception $exception) {

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
        $sample = resolve(SampleTransformer::class);
        $sample->setFavoriteSites($favoriteSites);
        $sample->setSimilarSites($similarSites);

        return $sample;
    }

    private function initialProjectData(Request $request): array
    {
        $agreementAt = $request->input('agreement_at');
        $deadlineAt = $request->input('deadline_at');

        $data = [
            'title' => $request->input('title'),
            'domain' => $request->input('domain_primary'),
            'type_id' => $request->input('type_id'),
            'user_id' => $request->input('user_id'),
            'base_id' => ProjectBase::Web,
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
            'package_id' => $request->input('package_id'),
            'pages' => $request->input('pages'),
            'domains' => $domain->toArray(),
            'host' => $host->toArray(),
            'language' => $language->toArray(),
            'sample' => $sample->toArray(),
        ];
    }
}
