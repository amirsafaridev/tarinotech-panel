<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Enums\Database\Admin\TypeInsurance;
use App\Enums\General\BtnType;
use App\Filters\Admin\Admin\JobTitleFilter;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Foundation\ValueObjects\Datatable\ExternalFilter;
use App\Helpers\Helper;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatableTrait;
use App\Traits\HasJsonCommonResponseTrait;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\Admin\app\Http\Requests\Admin\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\UpdateRequest;
use Modules\Admin\app\Models\Admin;
use Str;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    use HasDatatableTrait;
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'پرسنل';

    const CREATE_TITLE = 'پرسنل - ایجاد';

    const EDIT_TITLE = 'پرسنل - ویرایش';

    const SHOW_TITLE = 'پرسنل - نمایش';

    const PASSWORD_LENGTH = 8;

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();
        return view('admin::admin.index', compact('title', 'routeData', 'dataTable'));
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);
            $password = Str::random(self::PASSWORD_LENGTH);
            $item['password'] = bcrypt($password);
            $admin = Admin::query()->create($item);
            $admin->syncRoles($request->input('role'));
            DB::commit();

            //$admin->notify(new SendPasswordByEmail($password));

            return $this->successResponse();

        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(Admin $admin)
    {
        $admin->load(['roles', 'jobTitle']);

        $title = self::EDIT_TITLE;

        return view('admin::admin.edit', compact('title', 'admin'));
    }

    public function update(UpdateRequest $request, Admin $admin)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request, true);
            $item['is_block'] = $request->has('is_block');
            $admin->update($item);
            $admin->syncRoles($request->input('role'));
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show(Admin $admin)
    {
        $title = self::SHOW_TITLE;

        return view('admin::admin.show', compact('title', 'admin'));
    }

    public function destroy(Admin $admin)
    {
        try {
            DB::beginTransaction();
            $admin->update(['email' => uniqid($admin->email).'_']);
            $admin->delete();
            DB::commit();

            return $this->successDestroyBack(route('admin.admin.index'));
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionBack($exception);

        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $request, bool $editMode = false): array
    {
        $adminData['email'] = $request->input('email');
        $adminData['first_name'] = $request->input('first_name');
        $adminData['last_name'] = $request->input('last_name');
        $adminData['job_title_id'] = $request->input('job_title_id');
        $adminData['otp_send_way'] = $request->input('otp_send_way');

        $dob = $request->input('dob');
        $adminData['dob'] = empty($dob) ? null : Helper::toGregorian($dob);

        $startCooperation = $request->input('start_cooperation');
        $adminData['start_cooperation'] = empty($startCooperation) ? null : Helper::toGregorian($startCooperation);

        $startLastContract = $request->input('start_last_contract');
        $adminData['start_last_contract'] = empty($startLastContract) ? null : Helper::toGregorian($startLastContract);

        $endLastContract = $request->input('end_last_contract');
        $adminData['end_last_contract'] = empty($endLastContract) ? null : Helper::toGregorian($endLastContract);

        $adminData['resume'] = $request->input('resume');
        $adminData['description'] = $request->input('description');
        $adminData['mobile'] = $request->input('mobile');
        $adminData['mobile_company'] = $request->input('mobile_company');
        $adminData['number_company'] = $request->input('number_company');

        $adminData['tel'] = $request->input('tel');
        $adminData['postal_code'] = $request->input('postal_code');
        $adminData['work_location'] = $request->input('work_location');
        $adminData['type_insurance'] = $request->input('type_insurance');
        $adminData['has_contract'] = $request->has('has_contract');

        if ($request->input('promissory')) {
            $adminData['promissory'] = $request->input('promissory');
        }

        $adminData['national_code'] = $request->input('national_code');
        $adminData['shaba_number'] = $request->input('shaba_number');
        $adminData['cart_number'] = $request->input('cart_number');
        $adminData['is_foreign_national'] = $request->has('is_foreign_national');

        if (! $editMode) {
            $adminData['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('avatar')) {
            $provider = (new PhotoUploader())
                ->fit(150, 150)
                ->path('admin')
                ->field('avatar')
                ->upload();

            $adminData['avatar'] = $provider->getPath();
        }

        return $adminData;
    }

    public function getDataRoute(): string
    {
        return route('admin.admin.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(ColumnOption::new()->setName('id')->setAs('شناسه'))
            ->addColumn(ColumnOption::new()->setName('first_name')->setAs('نام'))
            ->addColumn(ColumnOption::new()->setName('last_name')->setAs('نام خانوادگی'))
            ->addColumn(ColumnOption::new()->setName('has_contract')->setAs('قرارداد'))
            ->addColumn(ColumnOption::new()->setName('end_last_contract')->setAs('پایان قرارداد'))
            ->addColumn(ColumnOption::new()->setName('type_insurance')->setAs('نوع بیمه'))
            ->addColumn(ColumnOption::new()->setName('promissory')->setAs('سفته'))
            ->addColumn(ColumnOption::new()->setName('is_block')->setAs('دسترسی'))
            ->addColumn(ColumnOption::new()->setName('action')->setAs('عملیات')->removeAction())
            ->addExternalFilter(ExternalFilter::new()->setKey('job_title'))
            ->render();
    }

    public function data()
    {
        try {
            $admins = Admin::query()
                ->filter([
                    JobTitleFilter::class,
                ])
                ->withCount('logins');

            return DataTables::eloquent($admins)
                ->editColumn('type_insurance', function (Admin $admin) {
                    return TypeInsurance::getDescription($admin->type_insurance);
                })
                ->editColumn('promissory', function (Admin $admin) {
                    return number_format($admin->promissory);
                })
                ->editColumn('end_last_contract', function (Admin $admin) {
                    if (! $admin->end_last_contract) {
                        return 'ثبت نشده';
                    }

                    return $admin->end_last_contract->toJalali()->format(formatJalaliDate());
                })
                ->addColumn('action', function (Admin $admin) {
                    $actions = Helper::btnMaker(BtnType::Warning, route('admin.admin.edit', $admin->id), trans('panel.action.edit'));
                    $actions .= Helper::btnMaker(BtnType::Info, route('admin.admin.password', $admin->id), trans('panel.action.change_password'));
                    $actions .= Helper::btnMaker(BtnType::Success, route('admin.admin.show', $admin->id), trans('panel.action.info'));

                    return $actions;
                })
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }
}