<?php

namespace Modules\Dashboard\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Modules\Admin\app\Models\Admin;

class HomeController extends Controller
{
    public function index()
    {

        $data = cache()->remember('dashboard', 0, function () {
            $data['admins_count'] = Admin::query()->count();

            return $data;
        });

        $title = trans('panel.dashboard.title');

        return view('dashboard::admin.index', compact('title', 'data'));
    }

    public function redirect()
    {
        return Redirect::route('admin.dashboard.index');
    }
}
