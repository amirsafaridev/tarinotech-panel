<?php

namespace Modules\Admin\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Traits\HasJsonCommonResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Admin\app\Http\Requests\Admin\PayslipTextManager\StoreRequest;
use Modules\Admin\app\Http\Requests\Admin\PayslipTextManager\UpdateRequest;
use Modules\Admin\app\Models\PayslipTextManager;

class PayslipTextManagerController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'مدیریت متن فیش حقوقی';
    const CREATE_TITLE = 'مدیریت متن فیش حقوقی - ایجاد';
    const EDIT_TITLE = 'مدیریت متن فیش حقوقی - ویرایش';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $payslipTextManagers = PayslipTextManager::query()->get();
        return view('admin::admin.payslip-text-manager.index', compact('title', 'payslipTextManagers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = self::CREATE_TITLE;

        return view('admin::admin.payslip-text-manager.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            PayslipTextManager::query()->create($inputs);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.payslip-text-manager.index'),
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
    public function edit(PayslipTextManager $payslipTextManager)
    {
        $title = self::EDIT_TITLE;

        return view('admin::admin.payslip-text-manager.edit', compact('title', 'payslipTextManager'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, PayslipTextManager $payslipTextManager)
    {
        
        try {
            DB::beginTransaction();
            $inputs = $request->all();
            $payslipTextManager->update($inputs);
            DB::commit();

            return response()->json([
                'result' => 'success',
                'back' => route('admin.admin.payslip-text-manager.index'),
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
    public function destroy(PayslipTextManager $payslipTextManager)
    {
        try {
            $payslipTextManager->delete();

            return $this->successDestroyBack(route('admin.admin.payslip-text-manager.index'));
        } catch (Exception $exception) {
            return $this->exceptionBack($exception);
        }
    }
}
