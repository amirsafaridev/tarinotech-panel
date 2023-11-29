<?php

namespace Modules\Auth\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\app\Models\Admin;
use Modules\Auth\app\Models\Login;

use function app;
use function redirect;
use function view;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    const INDEX_TITLE = 'تارینوتک - ورود';

    public function __construct()
    {
        $this->middleware('admin.guest:admin', ['except' => 'logout']);
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function index()
    {
        $title = self::INDEX_TITLE;

        if (app()->isLocal()) {
            Auth::guard('admin')->loginUsingId(1);
        }

        return view('auth::admin.login', compact('title'));
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect()->route('admin.dashboard');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'captcha' => 'required|captcha',
        ]);
    }

    /**
     * @param  Admin  $user
     */
    protected function authenticated(Request $request, $user)
    {
        $login = new Login();
        $login->userLogin($user);
    }

    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['is_block' => false]);
    }

    public function redirectPath()
    {
        return route('admin.home');
    }
}
