<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\FixedAmount\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\FixedAmount\UpdateRequest;
use Modules\Admin\app\Models\FixedAmount;
use Exception;

class FixedAmountController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'مبالغ ثابت';
    const CREATE_TITLE = 'مبالغ ثابت - ایجاد';
    const EDIT_TITLE = 'مبالغ ثابت - ویرایش';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $fixedAmounts = FixedAmount::query()->get();
        $fixedAmountsCount = FixedAmount::query()->count();
        return view('admin::admin.fixed-amount.index', compact('title', 'fixedAmounts', 'fixedAmountsCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.fixed-amount.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            FixedAmount::query()->create($inputs);
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
    public function edit(FixedAmount $fixedAmount)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.fixed-amount.edit', compact('title', 'fixedAmount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, FixedAmount $fixedAmount)
    {
        try {
            DB::beginTransaction();
            $fixedAmount->update($request->all());
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
    public function destroy(FixedAmount $fixedAmount)
    {
        try {
            $fixedAmount->delete();

            return $this->successDestroyBack(route('admin.admin.fixed-amount.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
