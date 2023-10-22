<?php

namespace App\Http\Controllers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Filters\Admin\Project\DomainFilter;
use App\Filters\Admin\Project\SortFilter;
use App\Filters\Admin\Project\StatusFilter;
use App\Filters\Admin\Project\Web\PackageFilter;
use App\Filters\Admin\Share\IDFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\Web\StoreRequest;
use App\Http\Requests\Admin\Project\Web\UpdateRequest;
use App\Models\Admin;
use App\Models\Project;
use App\Models\ProjectWeb;
use App\Service\Json\WebProject\DomainTransformer;
use App\Service\Json\WebProject\HostTransformer;
use App\Service\Json\WebProject\LanguageTransformer;
use App\Service\Json\WebProject\SampleTransformer;
use Crypt;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WebProjectController extends Controller
{
    public function index()
    {
        $title = 'پروژه ها - وب سایت ها';

        $projects = Project::query()
            ->with(['status', 'type.package'])
            ->whereHasMorph('type', [ProjectWeb::class], function (Builder $q) {
                $q->filter([
                    PackageFilter::class,
                ]);
            })
            ->filter([
                IDFilter::class,
                DomainFilter::class,
                StatusFilter::class,
                SortFilter::class,
            ])
            ->paginate(12);

        $sortItems = [
            'id-desc' => 'شناسه (نزولی)',
            'id-asc' => 'شناسه (صعودی)',
            'price-desc' => 'قیمت (نزولی)',
            'price-asc' => 'قیمت (صعودی)',
        ];

        return view('admin.project.web.index', compact('title', 'projects', 'sortItems'));
    }

    public function create()
    {
        $title = 'پروژه سایت - ایجاد';
        $routeStore = route('admin.project.web.store');

        return view('admin.project.web.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $projectWeb = ProjectWeb::query()->create($this->initialProjectData($request));
            $projectWeb->project()->create($this->initialWebProjectData($request));

            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($projectId)
    {
        $project = $this->getOrFailProject($projectId);

        $title = 'پروژه سایت - ویرایش';
        $routeUpdate = route('admin.project.web.update', $project->id);

        return view('admin.project.web.edit', compact('title', 'routeUpdate', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {

            $project = $this->getOrFailProject($projectId);

            DB::beginTransaction();
            $project->update($this->initialProjectData($request));
            $project->type->update($this->initialWebProjectData($request));
            DB::commit();

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($projectId)
    {
        $project = $this->getOrFailProject($projectId);

        $title = 'پروژه سایت - نمایش';

        return view('admin.project.web.show', compact('title', 'project'));
    }

    public function destroy(Admin $admin)
    {
        try {
            DB::beginTransaction();
            $admin->update(['email' => uniqid($admin->email).'_']);
            $admin->delete();
            DB::commit();

            return redirect(route('admin.project.web.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect(route('admin.project.web.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    private function getDomain(Request $req): DomainTransformer
    {

        $domainPassword = $req->input('domain_password') ?
            Crypt::encrypt($req->input('domain_password')) :
            '';
        $domains = resolve(DomainTransformer::class);
        $domains->setHaveDomain($req->has('have_domain'));
        $domains->setDomainProviderWebsite($req->input('domain_provider_website'));
        $domains->setDomainUsername($req->input('domain_username'));
        $domains->setDomainPassword($domainPassword);
        $domains->setDomainPrimary($req->input('domain_primary'));
        $domains->setDomainsRequired($req->input('domains_required'));
        $domains->setOtherDomain($req->input('other_domain'));

        return $domains;
    }

    private function getHost(Request $req): HostTransformer
    {
        $hostPassword = $req->input('host_password') ?
            Crypt::encrypt($req->input('host_password')) :
            '';

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
            'price' => $request->input('price'),
            'project_status_id' => $request->input('status_id'),
            'note' => $request->input('note'),
            'project_base_id' => ProjectBase::Web,
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

    private function initialWebProjectData(Request $request): array
    {
        $domain = $this->getDomain($request);
        $host = $this->getHost($request);
        $language = $this->getLanguage($request);
        $sample = $this->getSample($request);

        return [
            'field_activity' => $request->input('field_activity'),
            'package_id' => $request->input('package_id'),
            'project_type_id' => $request->input('project_type_id'),
            'pages' => $request->input('pages'),
            'working_days' => $request->input('working_days'),
            'domains' => $domain->toArray(),
            'host' => $host->toArray(),
            'language' => $language->toArray(),
            'sample' => $sample->toArray(),
            'facilities' => $request->input('facilities', []),
        ];
    }

    /**
     * @return Project|Project[]|Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\LaravelIdea\Helper\App\Models\_IH_Project_C|\LaravelIdea\Helper\App\Models\_IH_Project_QB|\LaravelIdea\Helper\App\Models\_IH_Project_QB[]|null
     */
    private function getOrFailProject($projectId): \LaravelIdea\Helper\App\Models\_IH_Project_C|array|null|Builder|Project|\LaravelIdea\Helper\App\Models\_IH_Project_QB|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
    {
        return Project::query()
            ->whereHasMorph('type', [ProjectWeb::class])
            ->with('type')
            ->findOrFail($projectId);
    }
}
