<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\Database\Role\PermissionName;
use App\Enums\Database\Role\RoleName;
use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Service\Json\SeoProject\HostTransformer;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Modules\Package\app\Models\Package;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Filters\IsSignFilter;
use Modules\Project\app\Filters\IsUserSignFilter;
use Modules\Project\app\Filters\Project\DateFilter;
use Modules\Project\app\Filters\Project\SearchFilter;
use Modules\Project\app\Filters\Project\SortFilter;
use Modules\Project\app\Filters\Seo\PackageFilter;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Http\Requests\Admin\Seo\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Seo\UpdateRequest;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectSeo;

class SeoController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پروژه های سئو';

    const CREATE_TITLE = 'پروژه های سئو - ایجاد';

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
            'admins.first_name as admin_first_name',
            'admins.last_name as admin_last_name',
            'users.first_name as user_first_name',
            'users.last_name as user_last_name',
            'project_types.title as project_types_title',
            'project_bases.title as project_bases_title',
            'project_statuses.title as project_statuses_title',
            'packages.title as packages_title',
            'project_seo.price_monthly as project_seo_price_monthly',
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
            ->join('project_seo', function ($join) {
                $join->on('projects.target_id', '=', 'project_seo.id')
                    ->where('projects.target_type', '=', ProjectSeo::class);
            })
            ->leftJoin('packages', 'project_seo.package_id', '=', 'packages.id')
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

        $projects = $projects->paginate();

        return view('project::admin.seo.index', compact('title', 'projects', 'hasPricePermission'));
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

            $projectData = $this->prepareProjectDataFromPackage($projectData, $request);

            $projectSeo = ProjectSeo::query()->create($projectData);

            $projectParams = $this->initialProjectData($request);
            $projectParams['tax_rate'] = config('factor.tax');

            $projectSeo->project()->create($projectParams);

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

        $adminId = auth()->id();
        if (hasAdminRole(RoleName::SUPER_ADMIN)) {
            $adminId = $request->input('admin_id');
        }
        $data = [
            'title' => $request->input('title'),
            'domain' => $request->input('domain_primary'),
            'admin_id' => $adminId,
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

    protected function prepareProjectDataFromPackage(array $projectData, StoreRequest $request): array
    {
        $package = Package::query()->find($request->get('package_id'));

        $projectData['keywords_count'] = $package->seo_keywords_count;
        $projectData['agreement_duration'] = $package->seo_agreement_duration;
        $projectData['amount_content'] = $package->seo_amount_content;

        $monthlyDuration = round($package->seo_agreement_duration / 30);

        if ($monthlyDuration <= 0) {
            throw new InvalidArgumentException('مدت زمان توافق باید بیشتر از صفر باشد.');
        }

        $pricePerMonth = round($request->get('price') / $monthlyDuration);

        $pricePerMonth = ($pricePerMonth * config('factor.tax')) + $pricePerMonth;

        $projectData['price_monthly'] = round($pricePerMonth);

        return $projectData;
    }
}
