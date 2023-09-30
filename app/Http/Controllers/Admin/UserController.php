<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Database\User\PersonType;
use App\Enums\Database\User\UserType;
use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Helpers\Uploader\FileUploader;
use App\Helpers\Uploader\Uploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\Address;
use App\Models\Company;
use App\Models\Irnic;
use App\Models\User;
use Crypt;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $title = 'مشتری ها';
        $routeData = route('admin.user.data');
        $selects = ['id', 'mobile', 'first_name', 'last_name', 'person_type', 'is_block', 'created_at'];

        return view('admin.user.index', compact('title', 'routeData', 'selects'));
    }

    public function data()
    {

        try {
            $users = User::query();

            return DataTables::of($users)
                ->editColumn('created_at', function (User $user) {
                    return $user->created_at->toJalali()->format('h:i Y-m-d');
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
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'ایجاد مشتری';
        $routeStore = route('admin.user.store');

        return view('admin.user.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $req)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($req);

            $user = User::create($item);

            if ($req->input('person_type') === PersonType::Legal) {
                $this->updateCompany($req, $user->id);
            }

            $this->updateIrnic($req, $user->id);
            $this->updateAddress($req, $user->id);

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
                'result1' => $e->getMessage(),
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(User $user)
    {
        $user->load(['address', 'company', 'irnic']);

        $title = 'ویرایش مشتری';
        $routeUpdate = route('admin.user.update', $user->id);
        $routeDestroy = route('admin.user.destroy', $user->id);

        return view('admin.user.edit', compact('title', 'user', 'routeUpdate', 'routeDestroy'));
    }

    public function update(UpdateRequest $req, User $user)
    {
        try {
            DB::beginTransaction();

            $item = $this->itemProvider($req);
            $user->update($item);

            if ($req->input('person_type') === PersonType::Legal) {
                $this->updateCompany($req, $user->id);
            }

            $this->updateIrnic($req, $user->id);
            $this->updateAddress($req, $user->id);

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
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(User $user)
    {
        $user->load(['address', 'company', 'irnic', 'projects']);
        $title = 'نمایش مشتری';

        return view('admin.user.show', compact('title', 'user'));
    }

    public function destroy(User $user)
    {
        try {
            $user->update([
                'mobile' => uniqid($user->mobile.'_'),
            ]);
            $user->delete();

            return redirect(route('admin.user.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.user.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $req): array
    {
        $item['first_name'] = $req->input('first_name');
        $item['last_name'] = $req->input('last_name');
        $item['en_first_name'] = $req->input('en_first_name');
        $item['en_last_name'] = $req->input('en_last_name');
        $item['father_name'] = $req->input('father_name');
        $item['national_id'] = $req->input('national_id');
        $item['document_id'] = $req->input('document_id');
        $item['tel'] = $req->input('tel');
        $item['email'] = $req->input('email');
        $item['person_type'] = $req->input('person_type');

        /** Not required in edit mode */
        if ($req->input('mobile')) {
            $item['mobile'] = $req->input('mobile');
        }

        $dob = $req->input('dob');
        $item['dob'] = empty($dob) ? null : Helper::toGregorian($dob);

        $item['official_bill'] = $req->has('official_bill');
        $item['is_block'] = $req->has('is_block');

        $item['user_type'] = UserType::Primary;

        if ($req->hasFile('avatar')) {
            $provider = (new Uploader())
                ->fit(250, 250)
                ->path('user')
                ->field('avatar')
                ->upload();
            $item['avatar'] = $provider['photo'];
        }

        if ($req->hasFile('national_photo')) {
            $provider = (new FileUploader());
            $provider->path('user')
                ->field('national_photo')
                ->upload();
            $item['national_photo'] = $provider->getPathStore();
        }

        return $item;
    }

    private function updateCompany(Request $req, int $userId)
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

    private function updateAddress(Request $req, int $userId)
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
}
