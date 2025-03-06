<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\Reason\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\Reason\UpdateRequest;
use Exception;
use Modules\Admin\app\Models\BonusesDeduction;
use Modules\Admin\app\Models\Reason;

class ReasonController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'علل';
    const CREATE_TITLE = 'علل - ایجاد';
    const EDIT_TITLE = 'علل - ویرایش';

    /**
     * Display a listing of the resource.
     */
    public function index(BonusesDeduction $bonusesDeduction)
    {
        $title = self::INDEX_TITLE;
        $reasons = $bonusesDeduction->reasons()->get();
        return view('admin::admin.reason.index', compact('title', 'reasons', 'bonusesDeduction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(BonusesDeduction $bonusesDeduction)
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.reason.create', compact('title', 'bonusesDeduction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request, BonusesDeduction $bonusesDeduction)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $inputs['user_id'] = Auth::user()->id;
            $inputs['type'] = 0;
            Reason::query()->create([
                'bonuses_deduction_id' => $bonusesDeduction->id,
                'description' => $inputs['description']
            ]);
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
    public function edit(BonusesDeduction $bonusesDeduction, Reason $reason)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.reason.edit', compact('title', 'reason', 'bonusesDeduction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Reason $reason)
    {
        try {
            DB::beginTransaction();
            $reason->update($request->all());
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
    public function destroy(BonusesDeduction $bonusesDeduction, Reason $reason)
    {
        try {
            $reason->delete();

            return $this->successDestroyBack(route('admin.admin.reason.index', $bonusesDeduction->id));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
