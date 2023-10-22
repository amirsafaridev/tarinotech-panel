<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Database\Factor\FactorStatus;
use App\Enums\Database\User\PersonType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Factor\StoreRequest;
use App\Http\Requests\Admin\Factor\UpdateRequest;
use App\Models\Admin;
use App\Models\Factor;
use App\Models\Project;
use App\Models\ProjectAds;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class FactorController extends Controller
{
    public function index()
    {
        $title = 'فاکتور ها';

        /*$projects = Project::query()
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
        ];*/

        return view('admin.factor.index', compact('title'));
    }

    public function create()
    {
        $title = 'فاکتور ها - ایجاد';
        $routeStore = route('admin.factor.store');

        return view('admin.factor.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            Factor::create($this->itemProvider($request));
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
            ->whereHasMorph('type', [ProjectAds::class])
            ->with('type')
            ->findOrFail($projectId);

        $title = 'پروژه گوگل ادز - ویرایش';
        $routeUpdate = route('admin.project.ads.update', $project->id);

        return view('admin.project.ads.edit', compact('title', 'routeUpdate', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = Project::query()
                ->whereHasMorph('type', [ProjectAds::class])
                ->with('type')
                ->findOrFail($projectId);

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

            return redirect(route('admin.admin.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect(route('admin.admin.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['project_id'] = $request->input('project_id');
        $item['title'] = $request->input('title');

        $item['expired_at'] = Helper::toGregorian($request->input('expired_at'));
        $item['status'] = FactorStatus::Pending;

        $item['gateway_data'] = [];
        $item['admin_id'] = auth()->id();

        $item['is_official'] = false;

        $project = Project::with('user')->find($item['project_id']);
        if ($project) {
            $user = $project->user;
            if ($user && ($user->person_type === PersonType::Legal || $user->official_bill)) {
                $item['is_official'] = true;
            }
        }

        return $item;
    }
}
