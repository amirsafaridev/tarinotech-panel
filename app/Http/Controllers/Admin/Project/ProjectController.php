<?php

namespace App\Http\Controllers\Admin\Project;

use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use DB;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
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
                    return $admin->created_at->toJalali()->format('h:i Y-m-d');
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

    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();

            return redirect(route('admin.admin.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            DB::rollBack();
            report($e);

            return redirect(route('admin.admin.index'))->with('danger', trans('panel.error_delete'));
        }
    }
}
