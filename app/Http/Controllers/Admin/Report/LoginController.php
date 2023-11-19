<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use Modules\Login\app\Models\Login;

use function view;

class LoginController extends Controller
{
    public function index()
    {
        $title = 'گزارش ورود';

        $logins = Login::query()
            ->paginate();

        return view('admin.report.login.index', compact('title', 'logins'));
    }

    public function show(Login $login)
    {
        $title = 'نمایش گزارش ورود';

        return view('admin.report.login.show', compact('title', 'login'));
    }
}
