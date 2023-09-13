<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Uploader\Uploader;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\PasswordUpdateRequest;
use App\Http\Requests\Admin\Profile\UpdateRequest;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $title = trans('panel.profile.edit');
        $routeUpdate = route('admin.profile.update');
        $admin = Admin::find(auth()->id());

        return view('admin.profile.edit', compact('title', 'admin', 'routeUpdate'));
    }

    public function password()
    {
        $title = 'کدبرگر | پروفایل | تغییر گذرواژه';
        $routeUpdate = route('admin.profile.password.update');

        return view('admin.profile.edit_password', compact('title', 'routeUpdate'));
    }

    public function update(UpdateRequest $request)
    {
        try {
            $admin = Admin::find(auth()->id());
            $this->setProfileData($request, $admin);
            $admin->update();

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

    public function updatePassword(PasswordUpdateRequest $request)
    {
        try {
            $admin = Admin::find(auth()->id());
            if (! Hash::check($request->get('current_password'), $admin->password)) {
                return response()->json([
                    'result' => 'warning',
                    'message' => 'گذرواژه وارد شده صحیح نیست!',
                ]);
            }

            $admin->password = bcrypt($request->get('new_password'));
            $admin->update();

            return response()->json([
                'result' => 'success',
                'message' => 'گذرواژه با موفقیت به روز شد.',
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'result' => 'exception',
                'message' => $exception->getMessage(),
            ], 500);
        }

    }

    protected function setProfileData(Request $request, Admin $admin): void
    {
        $admin->first_name = $request->get('first_name');
        $admin->last_name = $request->get('last_name');
        if ($request->hasFile('avatar')) {
            $provider = (new Uploader())
                ->fit(150, 150)
                ->path('admin')
                ->field('avatar')
                ->oldDelete($admin->avatar)
                ->upload();
            $admin->avatar = $provider['photo'];
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();

        return redirect()->route('admin.dashboard');
    }
}
