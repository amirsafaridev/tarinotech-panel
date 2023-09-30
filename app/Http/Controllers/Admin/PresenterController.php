<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Database\User\UserType;
use App\Enums\General\BtnType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Presenter\StoreRequest;
use App\Http\Requests\Admin\Presenter\UpdateRequest;
use App\Models\Project;
use App\Models\User;
use DB;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PresenterController extends Controller
{
    public function index()
    {
        $title = 'مدیریت نمایندگان';
        $routeData = route('admin.presenter.data');
        $selects = ['id', 'mobile', 'first_name', 'last_name', 'access_projects', 'is_block', 'created_at'];

        $skipSearch = ['access_projects'];
        $skipSort = ['access_projects'];

        return view('admin.presenter.index', compact('title', 'routeData', 'selects', 'skipSearch', 'skipSort'));
    }

    public function data()
    {
        try {
            $users = User::query()
                ->with('accessProjects')
                ->where('user_type', UserType::Presenter);

            return DataTables::of($users)
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->toJalali()->format('h:i Y-m-d');
                })
                ->editColumn('access_projects', function ($user) {
                    return $user->accessProjects ? $user->accessProjects->pluck('title')->implode(', ') : 'پروژه ای ندارد';
                })
                ->addColumn('action', function ($user) {
                    $actions = Helper::btnMaker(BtnType::Warning, route('admin.presenter.edit', $user->id), trans('panel.action.edit'));
                    $actions .= Helper::btnMaker(BtnType::Info, route('admin.presenter.show', $user->id), trans('panel.action.show'));

                    return $actions;
                })
                ->make();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $title = 'ایجاد نماینده';
        $routeStore = route('admin.presenter.store');

        return view('admin.presenter.create', compact('title', 'routeStore'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $user = User::create($item);
            $user->accessProjects()->sync($request->get('project_ids'));
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
                'message' => trans('panel.error_store'),
            ], 500);
        }
    }

    public function edit(User $user)
    {
        $user->load('accessProjects');

        $title = 'ویرایش نماینده';
        $routeUpdate = route('admin.presenter.update', $user->id);
        $routeDestroy = route('admin.presenter.destroy', $user->id);

        return view('admin.presenter.edit', compact('title', 'user', 'routeUpdate', 'routeDestroy'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();
            $item = $this->itemProvider($request);
            $user->update($item);
            $user->accessProjects()->sync($request->get('project_ids'));
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
                'message' => trans('panel.error_update'),
            ], 500);
        }
    }

    public function show(User $user)
    {
        $user->load(['accessProjects', 'latestLogin']);

        $title = 'نمایش نماینده';

        return view('admin.presenter.show', compact('title', 'user'));
    }

    public function destroy(Project $role)
    {
        try {
            $role->delete();

            return redirect(route('admin.presenter.index'))->with('success', trans('panel.success_delete'));
        } catch (Exception $e) {
            report($e);

            return redirect(route('admin.presenter.index'))->with('danger', trans('panel.error_delete'));
        }
    }

    protected function itemProvider(Request $req): array
    {
        $item['first_name'] = $req->input('first_name');
        $item['last_name'] = $req->input('last_name');
        $item['tel'] = $req->input('tel');
        $item['email'] = $req->input('email');

        /** Not required in edit mode */
        if ($req->input('mobile')) {
            $item['mobile'] = $req->input('mobile');
        }

        $item['user_type'] = UserType::Presenter;
        $item['is_block'] = $req->has('is_block');

        return $item;
    }
}
