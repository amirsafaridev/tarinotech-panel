<?php

namespace Modules\User\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Helpers\Uploader\FileUploader;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Http\Requests\Admin\User\StoreRequest;
use Modules\User\app\Http\Requests\Admin\User\UpdateRequest;
use Modules\User\app\Models\Address;
use Modules\User\app\Models\Company;
use Modules\User\app\Models\Irnic;
use Modules\User\app\Models\UseCellphone;
use Modules\User\app\Models\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'کارفرمایان';

    const CREATE_TITLE = 'ایجاد کارفرما';

    const EDIT_TITLE = 'ویرایش کارفرما';

    const SHOW_TITLE = 'نمایش کارفرما';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('user::admin.user.index', compact('title', 'routeData', 'dataTable'));
    }

    public function data()
    {
        try {
            $users = User::query();

            return DataTables::eloquent($users)
                ->editColumn('created_at', function (User $user) {
                    return $user->created_at->toJalali()->format(formatJalaliDateTime());
                })
                ->editColumn('person_type', function (User $user) {
                    return Helper::renderPersonType($user->person_type);
                })
                ->addColumn('action', function (User $user) {
                    $actions = Helper::btnMaker(BtnType::Warning, route('admin.user.edit', $user->id), trans('panel.action.edit'));
                    $actions .= Helper::btnMaker(BtnType::Info, route('admin.user.show', $user->id), trans('panel.action.show'));

                    return $actions;
                })
                ->rawColumns(['action', 'person_type'])
                ->make();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('user::admin.user.create', compact('title'));
    }

    public function store(StoreRequest $req)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($req);
            $item['is_block'] = false;

            $user = User::query()
                ->create($item);

            if ($req->input('person_type') === PersonType::Legal) {
                $this->updateCompany($req, $user->id);
            }

            $this->updateIrnic($req, $user->id);

            $this->updateAddress($req, $user->id);

            $this->syncCellphones($user);

            if (count($req->input('communications', []))) {
                $this->syncCommunications($req, $user);
            }

            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(User $user)
    {
        $user->load(['address', 'company', 'irnic', 'phones']);

        $title = self::EDIT_TITLE;

        return view('user::admin.user.edit', compact('title', 'user'));
    }

    public function update(UpdateRequest $req, User $user)
    {
        try {
            DB::beginTransaction();

            $item = $this->prepareItemData($req);
            $item['is_block'] = $req->has('is_block');

            $user->update($item);

            if ($req->input('person_type') === PersonType::Legal) {
                $this->updateCompany($req, $user->id);
            }

            $this->updateIrnic($req, $user->id);

            $this->updateAddress($req, $user->id);

            $this->syncCellphones($user);

            if (count($req->input('communications', []))) {
                $this->syncCommunications($req, $user);
            }

            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show(User $user)
    {
        $user->load(['address', 'company', 'irnic', 'projects', 'latestLogin']);

        $title = self::SHOW_TITLE;

        return view('user::admin.user.show', compact('title', 'user'));
    }

    public function destroy(User $user)
    {
        try {

            $user->update([
                'mobile' => uniqid($user->mobile.'_'),
                'deleted_at' => now(),
            ]);

            return $this->successDestroyBack(route('admin.user.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $req): array
    {
        $userData['first_name'] = $req->input('first_name');
        $userData['last_name'] = $req->input('last_name');
        $userData['en_first_name'] = $req->input('en_first_name');
        $userData['en_last_name'] = $req->input('en_last_name');
        $userData['father_name'] = $req->input('father_name');
        $userData['national_id'] = $req->input('national_id');
        $userData['document_id'] = $req->input('document_id');

        $userData['email'] = $req->input('email') ?? null;
        $userData['person_type'] = $req->input('person_type');

        $userData['knowledge_way_id'] = $req->input('knowledge_way_id');
        $userData['knowledge_way'] = $req->input('knowledge_way');
        $userData['mobile'] = $req->input('mobile');

        $dob = $req->input('dob');
        $userData['dob'] = empty($dob) ? null : Helper::toGregorian($dob);

        $userData['official_bill'] = $req->has('official_bill');

        $userData['user_type'] = UserType::Primary;

        if ($req->hasFile('avatar')) {
            $imageUploader = (new PhotoUploader())
                ->fit(250, 250)
                ->path('user')
                ->field('avatar')
                ->upload();

            $userData['avatar'] = $imageUploader->getPath();
        }

        if ($req->hasFile('national_photo')) {
            $provider = (new FileUploader());
            $provider->path('user')
                ->field('national_photo')
                ->upload();
            $userData['national_photo'] = $provider->getPathStore();
        }

        return $userData;
    }

    private function updateCompany(Request $req, int $userId): void
    {
        Company::query()->updateOrCreate([
            'user_id' => $userId,
        ], [
            'name' => $req->input('company_name'),
            'identify' => $req->input('company_identify'),
            'register_id' => $req->input('company_register_id'),
            'type' => $req->input('company_type'),
            'user_id' => $userId,
        ]);
    }

    private function updateAddress(Request $req, int $userId): void
    {
        Address::query()->updateOrCreate([
            'user_id' => $userId,
        ], [
            'address' => $req->input('address'),
            'postal_code' => $req->input('postal_code'),
            'user_id' => $userId,
        ]);
    }

    private function updateIrnic(Request $req, int $userId): void
    {
        Irnic::query()->updateOrCreate([
            'user_id' => $userId,
        ], [
            'status' => $req->input('irnic_status'),
            'identify' => $req->input('irnic_identify', ''),
            'password' => $req->input('irnic_password') ? Crypt::encrypt($req->input('irnic_password')) : '',
            'user_id' => $userId,
        ]);
    }

    public function getDataRoute(): string
    {
        return route('admin.user.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()->setName('id')
                    ->setAs('شناسه')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('mobile')
                    ->setAs('موبایل')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('first_name')
                    ->setAs('نام')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('last_name')
                    ->setAs('نام خانوادگی')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('person_type')
                    ->setAs('نوع کاربر')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('is_block')
                    ->setAs('وضعیت')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('created_at')
                    ->setAs('تاریخ ایجاد')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('action')
                    ->removeAction()
                    ->setAs('عملیات')
            )
            ->render();
    }

    private function syncCellphones(User $user)
    {
        UseCellphone::query()
            ->where('user_id', $user->id)
            ->delete();

        $cellphonesText = request()->input('cellphones');
        if (! $cellphonesText) {
            return;
        }
        $cellphonesArray = explode(PHP_EOL, $cellphonesText);
        $cellphonesArray = array_filter($cellphonesArray, 'trim');
        foreach ($cellphonesArray as $cellphone) {
            $user->phones()->create(['phone' => $cellphone]);
        }
    }

    private function syncCommunications(Request $req, User $user)
    {
        $user->communications()->sync($req->input('communications'));
    }
}
