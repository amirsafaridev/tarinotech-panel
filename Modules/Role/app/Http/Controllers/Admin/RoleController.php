<?php

namespace Modules\Role\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Role\app\Http\Requests\Admin\StoreRequest;
use Modules\Role\app\Http\Requests\Admin\UpdateRequest;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'نقش ها';

    const CREATE_TITLE = 'نقش ها - ایجاد';

    const EDIT_TITLE = 'نقش ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $roles = Role::query()
            ->withCount('permissions')
            ->latest()
            ->paginate(10);

        return view('role::admin.index', compact('title', 'roles'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('role::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $role = Role::create($item);
            $role->givePermissionTo($request->input('permissions'));
            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Role $role)
    {
        $title = self::EDIT_TITLE;
        $permissionSelected = $role->permissions()->pluck('id')->toArray();

        return view('role::admin.edit', compact('title', 'role', 'permissionSelected'));
    }

    public function update(UpdateRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $role->update($item);
            $role->syncPermissions($request->input('permissions'));
            DB::commit();

            return $this->successUpdateResponse();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);

        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();

            return $this->successBack(route('admin.role.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['name'] = $request->input('name');

        return $item;
    }
}
