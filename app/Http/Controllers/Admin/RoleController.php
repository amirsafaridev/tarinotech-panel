<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRequest;
use App\Http\Requests\Admin\Role\UpdateRequest;
use DB;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $title = trans('panel.role.index');
        $routeData = route('admin.role.data');
        $selects = ['id', 'name', 'permissions_count', 'created_at'];

        return view('admin.role.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $roles = Role::query()->select('roles.*')->withCount('permissions');

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
        $title = trans('panel.role.create');
        $routeStore = route('admin.role.store');
        $permissions = Permission::all();

        return view('admin.role.create', compact('title', 'routeStore', 'permissions'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $role = Role::create($item);
            $role->givePermissionTo($request->get('permissions'));
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

    public function edit(Role $role)
    {
        $title = trans('panel.role.edit');
        $routeUpdate = route('admin.role.update', $role->id);
        $routeDestroy = route('admin.role.destroy', $role->id);
        $permissions = Permission::all();
        $permissionSelected = $role->permissions()->pluck('id')->toArray();

        return view('admin.role.edit', compact('title', 'routeUpdate', 'routeDestroy', 'role', 'permissions', 'permissionSelected'));
    }

    public function update(UpdateRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $role->update($item);
            $role->syncPermissions($request->get('permissions'));
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

    public function destroy(Role $role)
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
        $item['name'] = $request->get('name');

        return $item;
    }
}
