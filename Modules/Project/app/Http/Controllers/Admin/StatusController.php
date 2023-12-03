<?php

namespace Modules\Project\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Project\app\Http\Requests\Admin\Status\StoreRequest;
use Modules\Project\app\Http\Requests\Admin\Status\UpdateRequest;
use Modules\Project\app\Models\ProjectStatus;
use Modules\Project\app\Models\ProjectType;
use Yajra\DataTables\Facades\DataTables;

class StatusController extends Controller
{
    public function index()
    {
        $title = 'وضعیت پروژه ها';
        $routeData = route('admin.project.status.data');
        $selects = ['id', 'title', 'type.base.title', 'type.title', 'projects_count', 'created_at'];

        return view('admin.project_status.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $projectStatuses = ProjectStatus::query()
                ->select('project_statuses.*')
                ->withCount('projects')
                ->with('type.base');

            return DataTables::of($projectStatuses)
                ->editColumn('created_at', function (ProjectStatus $projectStatus) {
                    return $projectStatus->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (ProjectStatus $projectStatus) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.project.status.edit', $projectStatus->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'وضعیت جدید';
        $routeStore = route('admin.project.status.store');
        $projectTypes = $this->toCompose();

        return view('admin.project_status.create', compact('title', 'routeStore', 'projectTypes'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            ProjectStatus::create($item);
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
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(ProjectStatus $projectStatus)
    {
        $title = 'ویرایش وضعیت';
        $routeUpdate = route('admin.project.status.update', $projectStatus->id);
        $routeDestroy = route('admin.project.status.destroy', $projectStatus->id);
        $projectTypes = $this->toCompose();

        return view('admin.project_status.edit', compact('title', 'routeUpdate', 'routeDestroy', 'projectStatus', 'projectTypes'));
    }

    public function update(UpdateRequest $request, ProjectStatus $projectStatus)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $projectStatus->update($item);
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

    public function destroy(ProjectStatus $projectStatus)
    {
        try {
            $projectStatus->delete();

            return redirect(route('admin.project.status.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.project.status.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['type_id'] = $request->input('type_id');
        $item['note'] = $request->input('note');

        return $item;
    }

    /**
     * @return mixed[]
     */
    private function toCompose(): array
    {
        return ProjectType::query()
            ->with('base')
            ->get()
            ->mapWithKeys(function ($projectType) {
                return [$projectType->id => $projectType->base->title.' - '.$projectType->title];
            })->toArray();
    }
}
