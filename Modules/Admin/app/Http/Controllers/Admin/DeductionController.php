<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\BonusesDeduction\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\BonusesDeduction\UpdateRequest;
use Exception;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Models\BonusesDeduction;
use Modules\Admin\app\Models\Reason;

class DeductionController extends Controller
{

    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'کسورات';
    const CREATE_TITLE = 'کسورات - ایجاد';
    const EDIT_TITLE = 'کسورات - ویرایش';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $deductions = BonusesDeduction::query()->where('type', 1)->get();
        return view('admin::admin.deductions.index', compact('title', 'deductions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;
        $users = Admin::query()->get();
        $reasons = Reason::where('type',1)->get();

        return view('admin::admin.deductions.create', compact('title', 'users','reasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $inputs['type'] = 1;
           BonusesDeduction::query()->create($inputs);
           
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
    public function edit(BonusesDeduction $bonusesDeduction)
    {
        $title = self::EDIT_TITLE;
        $users = Admin::query()->get();
        $reasons = Reason::where('type',1)->get();

        return view('admin::admin.deductions.edit', compact('title', 'bonusesDeduction', 'reason', 'users', 'reasons'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, BonusesDeduction $bonusesDeduction)
    {
        try {
            DB::beginTransaction();
            $bonusesDeduction->update($request->all());
          

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
    public function destroy(BonusesDeduction $bonusesDeduction)
    {
        try {
            $bonusesDeduction->delete();
          
            return $this->successDestroyBack(route('admin.admin.deductions.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
