<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\StoreRequest;
use App\Http\Requests\Admin\Project\UpdateRequest;
use App\Models\Project;
use DB;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index()
    {
        $title = trans('panel.project.index');
        $routeData = route('admin.role.data');
        $selects = ['id', 'name', 'permissions_count', 'created_at'];

        return view('admin.project.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $roles = Project::query()->select('roles.*')->withCount('permissions');

            return DataTables::of($roles)
                ->editColumn('created_at', function ($role) {
                    return $role->created_at->toJalali()->format('h:i Y-m-d');
                })
                ->addColumn('action', function ($role) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.role.edit', $role->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = trans('panel.project.create');
        $routeStore = route('admin.role.store');
        $permissions = Permission::all();

        return view('admin.project.create', compact('title', 'routeStore', 'permissions'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $role = Project::create($item);
            $role->givePermissionTo($request->input('permissions'));
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

    public function edit(Project $role)
    {
        $title = trans('panel.project.edit');
        $routeUpdate = route('admin.role.update', $role->id);
        $routeDestroy = route('admin.role.destroy', $role->id);
        $permissions = Permission::all();
        $permissionSelected = $role->permissions()->pluck('id')->toArray();

        return view('admin.project.edit', compact('title', 'routeUpdate', 'routeDestroy', 'role', 'permissions', 'permissionSelected'));
    }

    public function update(UpdateRequest $request, Project $role)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $role->update($item);
            $role->syncPermissions($request->input('permissions'));
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

    public function destroy(Project $role)
    {
        try {
            $role->delete();

            return redirect(route('admin.role.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.role.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $request): array
    {
        $item['name'] = $request->input('name');

        return $item;
    }
}
