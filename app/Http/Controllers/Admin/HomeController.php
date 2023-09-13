<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    public function index()
    {

        $data = cache()->remember('dashboard', 0, function () {
            $data['admins_count'] = Admin::query()->count();

            return $data;
        });

        $title = trans('panel.dashboard.title');

        return view('admin.home.index', compact('title', 'data'));
    }

    public function redirect()
    {
        return Redirect::route('admin.dashboard');
    }
}
