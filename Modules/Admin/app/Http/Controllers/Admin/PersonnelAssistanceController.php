<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\PersonnelAssistance\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\PersonnelAssistance\UpdateRequest;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\PersonnelAssistance;

class PersonnelAssistanceController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'مساعده';
    const CREATE_TITLE = 'مساعده - ایجاد';
    const EDIT_TITLE = 'مساعده - ویرایش';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $personnelAssistances = PersonnelAssistance::query()->get();
        return view('admin::admin.personnel-assistance.index', compact('title', 'personnelAssistances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;
        $users = Admin::query()->get();

        return view('admin::admin.personnel-assistance.create', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            PersonnelAssistance::query()->create($inputs);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.personnel-assistance.index'),
                'message' => trans('panel.success_update'),
            ]);
            //  return $this->successResponse();
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
    public function edit(PersonnelAssistance $personnelAssistance)
    {
        $title = self::EDIT_TITLE;
        $users = Admin::query()->get();

        return view('admin::admin.personnel-assistance.edit', compact('title', 'personnelAssistance', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, PersonnelAssistance $personnelAssistance)
    {
        try {
            DB::beginTransaction();
            $personnelAssistance->update($request->all());
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.personnel-assistance.index'),
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
    public function destroy(PersonnelAssistance $personnelAssistance)
    {
        try {
            $personnelAssistance->delete();

            return $this->successDestroyBack(route('admin.admin.personnel-assistance.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
