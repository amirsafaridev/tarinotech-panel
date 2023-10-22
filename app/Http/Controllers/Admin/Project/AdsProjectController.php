<?php

namespace App\Http\Controllers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Filters\Admin\Project\DomainFilter;
use App\Filters\Admin\Project\SortFilter;
use App\Filters\Admin\Project\StatusFilter;
use App\Filters\Admin\Share\IDFilter;
use App\Filters\Admin\User\UserSearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\Ads\StoreRequest;
use App\Http\Requests\Admin\Project\Ads\UpdateRequest;
use App\Models\Admin;
use App\Models\Project;
use App\Models\ProjectAds;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdsProjectController extends Controller
{
    public function index()
    {
        $title = 'پروژه ها - گوگل ادز';

        $projects = Project::query()
            ->with(['status', 'type', 'user', 'admin'])
            ->whereHasMorph('type', [ProjectAds::class])
            ->whereHas('user', function (Builder $q) {
                $q->filter([
                    UserSearchFilter::class,
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

        return view('admin.project.ads.index', compact('title', 'projects', 'sortItems'));
    }

    public function create()
    {
        $title = 'پروژه  گوگل ادز - ایجاد';
        $routeStore = route('admin.project.ads.store');

        return view('admin.project.ads.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $projectAds = ProjectAds::query()->create($this->initialAdsProjectData($request));
            $projectAds->project()->create($this->initialProjectData($request));

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

        $title = 'پروژه گوگل ادز - ویرایش';
        $routeUpdate = route('admin.project.ads.update', $project->id);

        return view('admin.project.ads.edit', compact('title', 'routeUpdate', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = $this->getOrFailProject($projectId);

            DB::beginTransaction();
            $project->update($this->initialProjectData($request));
            $project->type->update($this->initialAdsProjectData($request));
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

    public function show($projectId)
    {
        $project = $this->getOrFailProject($projectId);

        $title = 'پروژه گوگل ادز - نمایش';

        return view('admin.project.ads.show', compact('title', 'project'));
    }

    public function destroy(Admin $admin)
    {
        try {
            DB::beginTransaction();
            $admin->update(['email' => uniqid($admin->email).'_']);
            $admin->delete();
            DB::commit();

            return redirect(route('admin.admin.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect(route('admin.admin.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    private function initialProjectData(Request $request): array
    {
        return [
            'title' => $request->input('title'),
            'domain' => $request->input('domain_primary'),
            'admin_id' => auth()->id(),
            'user_id' => $request->input('user_id'),
            'price' => 0,
            'project_status_id' => $request->input('status_id'),
            'note' => $request->input('note'),
            'project_base_id' => ProjectBase::Ads,
        ];
    }

    private function initialAdsProjectData(Request $req): array
    {
        return [
            'field_activity' => $req->input('field_activity'),
            'designed_by' => $req->input('designed_by'),
        ];
    }

    private function getOrFailProject($projectId)
    {
        return Project::query()
            ->whereHasMorph('type', [ProjectAds::class])
            ->with('type')
            ->findOrFail($projectId);
    }
}
