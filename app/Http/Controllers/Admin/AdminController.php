<?php

namespace App\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Helpers\Uploader\Uploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\StoreRequest;
use App\Http\Requests\Admin\Admin\UpdateRequest;
use App\Models\Admin;
use App\Notifications\Admin\Admin\SendPasswordByEmailNotification;
use DB;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Str;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        $title = trans('panel.admin.index');
        $routeData = route('admin.admin.data');
        $selects = ['id', 'email', 'first_name', 'last_name', 'roles', 'latest_login', 'created_at'];
        $skipSearch = [''];
        $skipSort = [''];

        return view('admin.admin.index', compact('title', 'routeData', 'selects', 'skipSearch', 'skipSort'));
    }

    public function data()
    {
        try {
            $admins = Admin::query()
                ->with(['roles', 'latestLogin'])
                ->withCount('logins')
                ->get();

            return DataTables::of($admins)
                ->editColumn('created_at', function ($admin) {
                    return $admin->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->editColumn('latest_login', function ($admin) {
                    return $admin->latestLogin ?
                        $admin->latestLogin->login_at->toJalali()->format('h:i Y-m-d') :
                        trans('panel.admin.not_login');
                })
                ->editColumn('roles', function ($admin) {
                    return $admin->roles ? $admin->roles->pluck('name')->implode(', ') :
                        trans('panel.admin.not_role');
                })
                ->addColumn('action', function ($admin) {
                    $actions = Helper::btnMaker(BtnType::Warning, route('admin.admin.edit', $admin->id), trans('panel.action.edit'));
                    $actions .= Helper::btnMaker(BtnType::Info, route('admin.admin.password', $admin->id), trans('panel.action.change_password'));
                    $actions .= Helper::btnMaker(BtnType::Success, route('admin.admin.show', $admin->id), trans('panel.action.info'));

                    return $actions;
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'پرسنل - ایجاد';
        $routeStore = route('admin.admin.store');
        $roles = Role::all();

        return view('admin.admin.create', compact('title', 'routeStore', 'roles'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $password = Str::random(8);
            $item['password'] = bcrypt($password);
            $admin = Admin::create($item);
            $admin->syncRoles($request->input('role'));
            DB::commit();

            //$admin->notify(new SendPasswordByEmailNotification($password));

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

    public function edit(Admin $admin)
    {
        $title = 'پرسنل - ویرایش';
        $routeUpdate = route('admin.admin.update', $admin->id);
        $routeDestroy = route('admin.admin.destroy', $admin->id);
        $roles = Role::all();

        $oldRoles = $admin->roles;

        return view('admin.admin.edit', compact('title', 'routeUpdate', 'routeDestroy', 'admin', 'roles', 'oldRoles'));
    }

    public function update(UpdateRequest $request, Admin $admin)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request, true);
            $admin->update($item);
            $admin->syncRoles($request->input('role'));
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

    protected function itemProvider(Request $request, bool $editMode = false): array
    {
        $item['first_name'] = $request->input('first_name');
        $item['last_name'] = $request->input('last_name');
        $item['has_access'] = $request->has('has_access');

        $dob = $request->input('dob');
        $item['dob'] = empty($dob) ? null : Helper::toGregorian($dob);

        $startCooperation = $request->input('start_cooperation');
        $item['start_cooperation'] = empty($startCooperation) ? null : Helper::toGregorian($startCooperation);

        $startLastContract = $request->input('start_last_contract');
        $item['start_last_contract'] = empty($startLastContract) ? null : Helper::toGregorian($startLastContract);

        $endLastContract = $request->input('end_last_contract');
        $item['end_last_contract'] = empty($endLastContract) ? null : Helper::toGregorian($endLastContract);

        $item['resume'] = $request->input('resume');
        $item['description'] = $request->input('description');
        $item['mobile'] = $request->input('mobile');
        $item['mobile_company'] = $request->input('mobile_company');
        $item['number_company'] = $request->input('number_company');

        $item['tel'] = $request->input('tel');
        $item['postal_code'] = $request->input('postal_code');
        $item['work_location'] = $request->input('work_location');
        $item['type_insurance'] = $request->input('type_insurance');
        $item['has_contract'] = $request->has('has_contract');

        if ($request->input('promissory')) {
            $item['promissory'] = $request->input('promissory');
        }

        $item['national_code'] = $request->input('national_code');
        $item['shaba_number'] = $request->input('shaba_number');
        $item['cart_number'] = $request->input('cart_number');

        if (! $editMode) {
            $item['email'] = $request->input('email');
            $item['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            $provider = (new Uploader())
                ->fit(150, 150)
                ->path('admin')
                ->field('avatar')
                ->upload();

            $item['avatar'] = $provider['photo'];
        }

        return $item;
    }
}
