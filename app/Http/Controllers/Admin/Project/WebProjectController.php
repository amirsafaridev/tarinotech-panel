<?php

namespace App\Http\Controllers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Helpers\Helper;
use App\Helpers\Uploader\Uploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\Web\StoreRequest;
use App\Models\Admin;
use App\Models\Project;
use App\Models\ProjectWeb;
use App\Service\Json\DomainTransformer;
use App\Service\Json\HostTransformer;
use App\Service\Json\LanguageTransformer;
use App\Service\Json\SampleTransformer;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;

class WebProjectController extends Controller
{
    public function index()
    {
        $title = 'پروژه ها - وب سایت ها';

        $projects = Project::query()
            ->with(['status', 'type.package'])
            ->whereHasMorph('type', [ProjectWeb::class])
            ->where('project_base_id', ProjectBase::Web)
            ->paginate(3);

        return view('admin.project.web.index', compact('title', 'projects'));
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

            $domain = $this->getDomain($request);
            $host = $this->getHost($request);
            $language = $this->getLanguage($request);
            $sample = $this->getSample($request);

            $projectWeb = ProjectWeb::query()->create([
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
            ]);

            $projectWeb->project()->create([
                'title' => $request->input('title'),
                'domain' => $request->input('domain_primary'),
                'admin_id' => auth()->id(),
                'user_id' => $request->input('user_id'),
                'price' => $request->input('price'),
                'project_status_id' => $request->input('status_id'),
                'deadline_at' => $request->input('deadline_at'),
                'note' => $request->input('note'),
                'project_base_id' => ProjectBase::Web,
                'agreement_at' => $request->input('agreement_at'),
            ]);

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
        $project = Project::query()
            ->whereHasMorph('type', [ProjectWeb::class])
            ->with('type')
            ->findOrFail($projectId);

        $title = 'پروژه سایت - ویرایش';
        $routeUpdate = route('admin.project.web.update', $project->type->id);
        // $routeDestroy = route('admin.project.web.destroy', $project->type->id);

        return view('admin.project.web.edit', compact('title', 'routeUpdate', 'project'));
    }

    public function update(UpdateRequest $request, Admin $admin)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request, true);
            $admin->update($item);
            $admin->syncRoles($request->input('role'));
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
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }

    public function show(Admin $admin)
    {
        $title = trans('panel.admin.show');

        return view('admin.admin.show', compact('title', 'admin'));
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

    protected function itemProvider(Request $request, bool $editMode = false): array
    {
        $item['first_name'] = $request->input('first_name');
        $item['last_name'] = $request->input('last_name');
        $item['password'] = bcrypt($request->input('password'));
        $item['has_access'] = $request->has('has_access');

        $dob = $request->input('dob');
        $item['dob'] = empty($dob) ? null : Helper::toGregorian($dob);

        $startCooperation = $request->input('start_cooperation');
        $item['start_cooperation'] = empty($startCooperation) ? null : Helper::toGregorian($startCooperation);

        $startLastContract = $request->input('start_last_contract');
        $item['start_last_contract'] = empty($startLastContract) ? null : Helper::toGregorian($startLastContract);

        $endLastContract = $request->input('end_last_contract');
        $item['end_last_contract'] = empty($endLastContract) ? null : Helper::toGregorian($endLastContract);

        $item['resume'] = $request->input('resume');
        $item['description'] = $request->input('description');
        $item['mobile'] = $request->input('mobile');

        if (! $editMode) {
            $item['email'] = $request->input('email');
            $item['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            $provider = (new Uploader())
                ->fit(150, 150)
                ->path('admin')
                ->field('avatar')
                ->upload();

            $item['avatar'] = $provider['photo'];
        }

        return $item;
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
}
