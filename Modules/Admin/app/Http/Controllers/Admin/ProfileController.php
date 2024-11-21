<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Enums\Database\Admin\OtpSendWay;
use App\Helpers\Uploader\PhotoUploader;
use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\app\Http\Requests\Admin\Profile\PasswordUpdateRequest;
use Modules\Admin\app\Http\Requests\Admin\Profile\UpdateRequest;
use Modules\Admin\app\Models\Admin;

class ProfileController extends Controller
{
    use HasJsonCommonResponse;

    const PASSWORD_TITLE = 'پروفایل - تغییر گذرواژه';

    const INVALID_OLD_PASSWORD = 'گذرواژه وارد شده صحیح نیست!';

    const EDIT_PROFILE_TITLE = 'ویرایش پروفایل';

    public function index()
    {
        $title = self::EDIT_PROFILE_TITLE;
        $admin = Admin::find(auth()->id());

        return view('admin::admin.profile.index', compact('title', 'admin'));
    }

    public function update(UpdateRequest $request)
    {
        try {
            $admin = Admin::find(auth()->id());

            $item = $this->prepareItemData($request);
            $admin->update($item);

            return response()->json([
                'result' => 'success',
                'message' => 'پروفایل با موفقیت به روز شد.',
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'result' => 'exception',
                'message' => $exception->getMessage(),
            ], 500);
        }

    }

    public function password()
    {
        $title = self::PASSWORD_TITLE;

        return view('admin::admin.profile.password', compact('title'));
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        try {
            $admin = Admin::find(auth()->id());
            if (! Hash::check($request->input('current_password'), $admin->password)) {
                return response()->json([
                    'result' => 'warning',
                    'message' => self::INVALID_OLD_PASSWORD,
                ]);
            }

            $newPassword = bcrypt($request->input('new_password'));
            $admin->update([
                'password' => $newPassword,
            ]);

            return $this->successResponse();
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }

    }

    /**
     * @throws Exception
     */
    protected function prepareItemData(Request $request): array
    {
        $adminData['first_name'] = $request->input('first_name');
        $adminData['last_name'] = $request->input('last_name');
        $adminData['otp_send_way'] = $request->input('otp_send_way');

        if ($request->input('otp_send_way') != OtpSendWay::GOOGLE_AUTH) {
            $adminData['google2fa_secret'] = null;
        }

        if ($request->hasFile('avatar')) {
            $imageUploader = (new PhotoUploader())
                ->fit(150, 150)
                ->path('admin')
                ->field('avatar')
                ->upload();

            $adminData['avatar'] = $imageUploader->getPath();
        }

        return $adminData;
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();

        return redirect()->route('admin.dashboard.index');
    }
}
