<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasJsonCommonResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\VariableAmount\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\VariableAmount\UpdateRequest;
use Exception;

use Modules\Admin\app\Models\VariableAmount;

class VariableAmountController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'مبالغ متغیر';
    const CREATE_TITLE = 'مبالغ متغیر - ایجاد';
    const EDIT_TITLE = 'مبالغ متغیر - ویرایش';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $variableAmounts = VariableAmount::query()->get();

        return view('admin::admin.variable-amount.index', compact('title', 'variableAmounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.variable-amount.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            VariableAmount::query()->create($inputs);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.variable-amount.index'),
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
    public function edit(VariableAmount $variableAmount)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.variable-amount.edit', compact('title', 'variableAmount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, VariableAmount $variableAmount)
    {
        try {
            DB::beginTransaction();
            $variableAmount->update($request->all());
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.variable-amount.index'),
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
    public function destroy(VariableAmount $variableAmount)
    {
        try {
            $variableAmount->delete();

            return $this->successDestroyBack(route('admin.admin.variable-amount.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
