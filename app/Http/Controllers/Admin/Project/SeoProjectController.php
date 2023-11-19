<?php

namespace App\Http\Controllers\Admin\Project;

use App\Enums\Database\Project\ProjectBase;
use App\Filters\Admin\Project\DomainFilter;
use App\Filters\Admin\Project\SortFilter;
use App\Filters\Admin\Project\StatusFilter;
use App\Filters\Admin\Share\IDFilter;
use App\Filters\Admin\User\UserSearchFilter;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\Seo\StoreRequest;
use App\Http\Requests\Admin\Project\Seo\UpdateRequest;
use App\Models\Project;
use App\Models\ProjectSeo;
use App\Service\Json\SeoProject\HostTransformer;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Admin\app\Models\Admin;

class SeoProjectController extends Controller
{
    public function index()
    {
        $title = 'پروژه ها - سئو';

        $projects = Project::query()
            ->with(['status', 'type', 'user', 'admin'])
            ->whereHasMorph('type', [ProjectSeo::class])
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

        return view('admin.project.seo.index', compact('title', 'projects', 'sortItems'));
    }

    public function create()
    {
        $title = 'پروژه سئو - ایجاد';
        $routeStore = route('admin.project.seo.store');

        return view('admin.project.seo.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $projectSeo = ProjectSeo::query()->create($this->initialSeoProjectData($request));
            $projectSeo->project()->create($this->initialProjectData($request));

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

        $title = 'پروژه سئو - ویرایش';
        $routeUpdate = route('admin.project.seo.update', $project->id);

        return view('admin.project.seo.edit', compact('title', 'routeUpdate', 'project'));
    }

    public function update(UpdateRequest $request, $projectId)
    {
        try {
            $project = $this->getOrFailProject($projectId);

            DB::beginTransaction();
            $project->update($this->initialProjectData($request));
            $project->type->update($this->initialSeoProjectData($request));
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

        $title = 'پروژه سئو - نمایش';

        return view('admin.project.seo.show', compact('title', 'project'));
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
        $agreementAt = $request->input('agreement_at');

        return [
            'title' => $request->input('title'),
            'domain' => $request->input('domain_primary'),
            'admin_id' => auth()->id(),
            'user_id' => $request->input('user_id'),
            'price' => $request->input('price'),
            'project_status_id' => $request->input('status_id'),
            'note' => $request->input('note'),
            'project_base_id' => ProjectBase::Seo,
            'agreement_at' => Helper::toGregorian($agreementAt),
        ];
    }

    private function initialSeoProjectData(Request $req): array
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
        ];
    }

    private function getHost(Request $req): HostTransformer
    {
        $host = resolve(HostTransformer::class);
        $host->setHostLocation($req->input('host_location'));
        $host->setHostProvider($req->input('host_provider'));

        return $host;
    }

    private function getOrFailProject($projectId)
    {
        return Project::query()
            ->whereHasMorph('type', [ProjectSeo::class])
            ->with('type')
            ->findOrFail($projectId);
    }
}
