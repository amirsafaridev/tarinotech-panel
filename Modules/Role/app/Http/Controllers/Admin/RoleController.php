<?php

namespace Modules\Role\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Role\app\Http\Requests\Admin\StoreRequest;
use Modules\Role\app\Http\Requests\Admin\UpdateRequest;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'نقش ها';

    const CREATE_TITLE = 'نقش ها - ایجاد';

    const EDIT_TITLE = 'نقش ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('role::admin.index', compact('title', 'routeData', 'dataTable'));
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

            return $this->successDestroyBack(route('admin.role.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $request): array
    {
        $item['name'] = $request->input('name');

        return $item;
    }

    public function getDataRoute(): string
    {
        return route('admin.role.data');
    }

    public function getDataTable(): array
    {
        $dataTable = new DatatableBase();
        $dataTable
            ->addColumn(
                ColumnOption::new()
                    ->setName('id')
                    ->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('name')
                    ->setAs('عنوان')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('permissions_count')
                    ->setAs('تعداد دسترسی')
                    ->setSearchable(false)
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('created_at')
                    ->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            );

        return $dataTable->render();
    }

    public function data()
    {
        try {
            $roles = Role::query()
                ->withCount('permissions');

            return DataTables::eloquent($roles)
                ->editColumn('created_at', function (Role $role) {
                    return $role->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function (Role $role) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.role.edit', $role->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
