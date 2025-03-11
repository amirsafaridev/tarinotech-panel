<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasJsonCommonResponseTrait;
use Modules\Personnel\app\Models\Payslip;
use Modules\Personnel\Services\PayslipService;
use Modules\Admin\app\Models\FixedAmount;
use Modules\Admin\app\Models\VariableAmount;
use Modules\Admin\app\Models\PersonnelSalary;
use Modules\Admin\app\Models\PersonnelAssistance;

class PayslipController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'فیش های حقوقی';
    const CREATE_TITLE = 'فیش حقوقی - ایجاد';
    const EDIT_TITLE = 'فیش حقوقی - ویرایش';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = self::INDEX_TITLE;
        $user = Auth::user();
        $payslips = Payslip::where('user_id',$user->id)->get();
        $payslipService = new PayslipService();

        if(Payslip::count()===0)
        {
            $payslipService->generatePayslips();
        }
        return view('personnel::admin.payslip.index', compact('title', 'payslips'));
    }
    public function show(Payslip $payslip)
    {
        $fixedAmount = FixedAmount::orderBy('created_at','desc')->first();
        $variableAmount = VariableAmount::orderBy('created_at','desc')->first();
        $personnelSalay = PersonnelSalary::where('user_id', $payslip->user->id)->first();
        $personnelAssistance = PersonnelAssistance::where('user_id', $payslip->user->id)->first();

        return view('personnel::admin.payslip.show', compact('payslip','fixedAmount','variableAmount','personnelSalay','personnelAssistance'));
    }
}
