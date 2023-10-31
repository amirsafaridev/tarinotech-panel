<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectType\StoreRequest;
use App\Http\Requests\Admin\ProjectType\UpdateRequest;
use App\Models\Facility;
use App\Models\Project;
use App\Models\ProjectBase;
use App\Models\ProjectType;
use Exception;
use Illuminate\Http\Request;

class ProjectFacilityController extends Controller
{
    public function index(Project $project)
    {
        $title = 'امکانات جانبی';

        return view('admin.project_facility.index', compact('title', 'project'));
    }

    public function create(Project $project)
    {
        $title = 'امکانات جانبی - ایجاد';
        $routeStore = route('admin.project.facility.store', $project->id);
        $facilities = Facility::query()->get();

        return view('admin.project_type.create', compact('title', 'routeStore', 'project', 'facilities'));
    }

    public function store(StoreRequest $request)
    {
        try {

            $item = $this->itemProvider($request);
            ProjectType::create($item);

            return response()->json([
                'result' => 'success',
                'message' => trans('panel.success_store'),
            ]);
        } catch (Exception $e) {

            report($e);

            return response()->json([
                'result' => 'exception',
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(ProjectType $projectType)
    {
        $title = 'انواع پزوژه ها - ویرایش';
        $routeUpdate = route('admin.project.type.update', $projectType->id);
        $routeDestroy = route('admin.project.type.destroy', $projectType->id);
        $projectBases = ProjectBase::query()->get();

        return view('admin.project_type.edit', compact('title', 'routeUpdate', 'routeDestroy', 'projectType', 'projectBases'));
    }

    public function update(UpdateRequest $request, ProjectType $projectType)
    {
        try {

            $item = $this->itemProvider($request);
            $projectType->update($item);

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

    public function destroy(ProjectType $projectType)
    {
        try {
            $projectType->delete();

            return redirect(route('admin.project.type.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.project.type.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['title'] = $request->input('title');
        $item['project_base_id'] = $request->input('project_base_id');
        $item['note'] = $request->input('note');

        return $item;
    }
}
