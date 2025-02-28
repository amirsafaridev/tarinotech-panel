<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\PersonnelReport\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\PersonnelReport\UpdateRequest;
use Exception;

use Modules\Admin\app\Models\PersonnelReport;
class PersonnelReportController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'گزارش تردد و مرخصی';
    const CREATE_TITLE = 'گزارش تردد و مرخصی - ایجاد';
    const EDIT_TITLE = 'گزارش تردد و مرخصی - ویرایش';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $personnelReports = PersonnelReport::query()->get();
        return view('admin::admin.personnel-report.index',compact('title','personnelReports'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.personnel-report.create',compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs=$request->all();
            $inputs['user_id']=Auth::user()->id;
            PersonnelReport::query()->create($inputs);
            DB::commit();

            return $this->successResponse();

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
    public function edit(PersonnelReport $personnelReport)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.personnel-report.edit',compact('title','personnelReport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, PersonnelReport $personnelReport)
    {
        try {
            DB::beginTransaction();
            $personnelReport->update($request->all());
            DB::commit();

            return $this->successUpdateResponse();
        } catch (Exception $exception) {
            DB::rollBack();

            return $this->exceptionResponse($exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PersonnelReport $personnelReport)
    {
        try {
            $personnelReport->delete();

            return $this->successDestroyBack(route('admin.admin.personnel-report.index'));

        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
