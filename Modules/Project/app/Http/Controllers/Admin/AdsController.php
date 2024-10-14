<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\Database\Role\PermissionName;
use App\Filters\Admin\Admin\AdminJoinedFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Filters\IsSignFilter;
use Modules\Project\app\Filters\IsUserSignFilter;
use Modules\Project\app\Filters\Project\DateFilter;
use Modules\Project\app\Filters\Project\SortFilter;
use Modules\Project\app\Filters\StatusFilter;
use Modules\Project\app\Http\Requests\Admin\Ads\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Ads\UpdateRequest;
use Modules\Project\app\Models\Project;
use Modules\Project\app\Models\ProjectAds;

class AdsController extends Controller
{
    const INDEX_TITLE = 'پروژه های ادز';

    const CREATE_TITLE = 'پروژه های ادز - ایجاد';

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
            'project_ads.field_activity as project_ads_field_activity',
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
            ->join('project_ads', function ($join) {
                $join->on('projects.target_id', '=', 'project_ads.id')
                    ->where('projects.target_type', '=', ProjectAds::class);
            })
            ->filter([
                AdminJoinedFilter::class,
                StatusFilter::class,
                IsSignFilter::class,
                IsUserSignFilter::class,
                DateFilter::class,
                SortFilter::class,
            ]);

        $projects = $projects->paginate();

        return view('project::admin.ads.index', compact('title', 'projects', 'hasPricePermission'));
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

        $title = self::EDIT_TITLE.' - '.$project->title;

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
}
