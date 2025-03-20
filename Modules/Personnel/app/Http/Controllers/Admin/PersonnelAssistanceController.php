<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Personnel\app\Http\Requests\Admin\PersonnelAssistance\StoreRequest;
use Modules\Personnel\app\Http\Requests\Admin\PersonnelAssistance\UpdateRequest;
use Modules\Admin\app\Models\PersonnelAssistance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


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
        $personnelAssistances = PersonnelAssistance::where('user_id', Auth::user()->id)->get();
        return view('personnel::admin.personnel-assistance.index', compact('title', 'personnelAssistances'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('personnel::admin.personnel-assistance.create', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $inputs['user_id']=Auth::user()->id;

            PersonnelAssistance::query()->create($inputs);
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
    public function edit(PersonnelAssistance $personnelAssistance)
    {
        $title = self::EDIT_TITLE;

        return view('personnel::admin.personnel-assistance.edit', compact('title', 'personnelAssistance'));
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

            return $this->successUpdateResponse();
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

            return $this->successDestroyBack(route('admin.personnel.personnel-assistance.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
    public function approved(PersonnelAssistance $personnelAssistance)
    {
        $personnelAssistance->status = 1;
        $personnelAssistance->save();
        return $this->successBack(route('admin.personnel.personnel-assistance.index'),'مساعده مورد نظر تایید شد');
    }
    public function canceled(Request $request, PersonnelAssistance $personnelAssistance)
    {
        $request->validate([
            'reject_reason' => 'required'
        ]);
        $personnelAssistance->update([
            'reject_reason' => $request->reject_reason,
            'status' => 2
        ]);
        return response()->json([
            'result' => 'success',
            'back' => route('admin.personnel.personnel-assistance.index'),
            'message' => 'مساعده مورد نظر رد شد',
        ]);
    }
}
