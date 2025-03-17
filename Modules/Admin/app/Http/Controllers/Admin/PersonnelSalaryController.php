<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\PersonnelSalary\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\PersonnelSalary\UpdateRequest;
use Exception;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\PersonnelSalary;

class PersonnelSalaryController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'تنخواه';
    const CREATE_TITLE = 'تنخواه - ایجاد';
    const EDIT_TITLE = 'تنخواه - ویرایش';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $personnelSalaries = PersonnelSalary::query()->get();
        return view('admin::admin.personnel-salary.index', compact('title', 'personnelSalaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;
        $users = Admin::query()->get();

        return view('admin::admin.personnel-salary.create', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            PersonnelSalary::query()->create($inputs);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.personnel-salary.index'),
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Show the specified resource.
    //  */
    // public function show($id)
    // {
    //     return view('admin::show');
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PersonnelSalary $personnelSalary)
    {
        $title = self::EDIT_TITLE;
        $users = Admin::query()->get();

        return view('admin::admin.personnel-salary.edit', compact('title', 'personnelSalary', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, PersonnelSalary $personnelSalary)
    {
        try {
            DB::beginTransaction();
            $personnelSalary->update($request->all());
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.personnel-salary.index'),
                'message' => trans('panel.success_update'),
            ]);
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonnelSalary $personnelSalary)
    {
        try {
            $personnelSalary->delete();

            return $this->successDestroyBack(route('admin.admin.personnel-salary.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
