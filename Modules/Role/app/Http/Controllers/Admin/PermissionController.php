<?php

namespace Modules\Role\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Service\PermissionService;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Modules\Role\app\Http\Requests\Admin\Permission\StoreRequest;
use Modules\Role\app\Http\Requests\Admin\Permission\UpdateRequest;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'پرمیشن ها';

    const CREATE_TITLE = 'پرمیشن ها - ایجاد';

    const EDIT_TITLE = 'پرمیشن ها - ویرایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('role::admin.permission.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('role::admin.permission.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            Permission::query()->create($this->prepareItemData($request));

            return $this->successResponse();

        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Permission $permission)
    {
        $title = self::EDIT_TITLE;

        return view('role::admin.permission.edit', compact('title', 'permission'));
    }

    public function update(UpdateRequest $request, Permission $permission)
    {
        try {
            $item = $this->prepareItemData($request);
            $permission->update($item);

            return $this->successUpdateResponse();
        } catch (Exception $exception) {

            return $this->exceptionResponse($exception);
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();

            return $this->successDestroyBack(route('admin.permission.index'));
        } catch (Exception $exception) {

            return $this->exceptionBack($exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $req): array
    {
        $permissionData['name'] = $req->input('name');
        $permissionData['title'] = $req->input('title');

        return $permissionData;
    }

    public function sync()
    {
        Permission::query()->where('id', '>', 0)->delete();
        $countCreated = resolve(PermissionService::class)->sync();

        return back()->with('success', sprintf('تعداد پرمیشن های جدید %s می باشد.', $countCreated));
    }

    public function getDataRoute(): string
    {
        return route('admin.permission.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()->setName('name')->setAs('نام')
            )
            ->addColumn(
                ColumnOption::new()->setName('title')->setAs('عنوان')
            )
            ->addColumn(
                ColumnOption::new()->setName('created_at')->setAs('ایجاد')
            )
            ->addColumn(
                ColumnOption::new()->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->render();
    }

    public function data()
    {
        try {
            $permissions = Permission::query();

            return DataTables::eloquent($permissions)
                ->editColumn('created_at', function ($permission) {
                    return $permission->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->addColumn('action', function ($permission) {
                    return Helper::btnMaker(BtnType::Warning, route('admin.permission.edit', $permission->id), trans('panel.action.edit'));
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}
