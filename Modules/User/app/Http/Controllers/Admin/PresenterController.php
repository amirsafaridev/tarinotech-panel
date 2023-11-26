<?php

namespace Modules\User\app\Http\Controllers\Admin;

use App\Enums\General\BtnType;
use App\Foundation\ValueObjects\Datatable\ColumnOption;
use App\Foundation\ValueObjects\Datatable\DatatableBase;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Traits\HasDatatable;
use App\Traits\HasJsonCommonResponse;
use DB;
use Exception;
use Illuminate\Http\Request;
use Modules\User\app\Enums\UserType;
use Modules\User\app\Http\Requests\Admin\Presenter\StoreRequest;
use Modules\User\app\Http\Requests\Admin\Presenter\UpdateRequest;
use Modules\User\app\Models\User;
use Yajra\DataTables\Facades\DataTables;

class PresenterController extends Controller
{
    use HasDatatable;
    use HasJsonCommonResponse;

    const INDEX_TITLE = 'نمایندگان';

    const CREATE_TITLE = 'نمایندگان - ایجاد';

    const EDIT_TITLE = 'نمایندگان - ویرایش';

    const SHOW_TITLE = 'نمایندگان - نمایش';

    public function index()
    {
        $title = self::INDEX_TITLE;

        $routeData = $this->getDataRoute();

        $dataTable = $this->getDataTable();

        return view('user::admin.presenter.index', compact('title', 'routeData', 'dataTable'));
    }

    public function data()
    {
        try {
            $users = User::query()
                ->with('accessProjects')
                ->where('user_type', UserType::Presenter);

            return DataTables::eloquent($users)
                ->editColumn('created_at', function ($user) {
                    return $user->created_at->toJalali()->format(formatJalaliDateTime());
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
        } catch (Exception $exception) {
            return $this->exceptionResponse($exception);
        }
    }

    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('user::admin.presenter.create', compact('title'));
    }

    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $item = $this->prepareItemData($request);

            $user = User::query()
                ->create($item);

            $user->accessProjects()->sync($request->input('project_ids'));

            DB::commit();

            return $this->successResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function edit(User $user)
    {
        $user->load('accessProjects');

        $title = self::EDIT_TITLE;

        return view('user::admin.presenter.edit', compact('title', 'user'));
    }

    public function update(UpdateRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            $item = $this->prepareItemData($request);
            $user->update($item);

            $user->accessProjects()->sync($request->input('project_ids'));
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    public function show(User $user)
    {
        $user->load(['accessProjects', 'latestLogin']);

        $title = self::SHOW_TITLE;

        return view('user::admin.presenter.show', compact('title', 'user'));
    }

    public function destroy(User $user)
    {
        try {
            $user->update([
                'mobile' => uniqid($user->mobile.'_'),
            ]);
            $user->delete();

            return $this->successDestroyBack(route('admin.presenter.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }

    protected function prepareItemData(Request $req): array
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

    public function getDataRoute(): string
    {
        return route('admin.presenter.data');
    }

    public function getDataTable(): array
    {
        return (new DatatableBase())
            ->addColumn(
                ColumnOption::new()
                    ->setName('id')
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
                    ->setName('access_projects')
                    ->setAs('پروژه ها')
                    ->setSearchable(false)
                    ->setSortable(false)
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('is_block')
                    ->setAs('مسدود شده')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('created_at')
                    ->setAs('تاریخ ایجاد')
            )
            ->addColumn(
                ColumnOption::new()
                    ->setName('action')
                    ->setAs('عملیات')
                    ->removeAction()
            )
            ->render();
    }
}
