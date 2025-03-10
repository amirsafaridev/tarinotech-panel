<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasJsonCommonResponseTrait;
use Modules\Personnel\app\Models\Payslip;

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
        return view('personnel::admin.payslip.index', compact('title', 'payslips'));
    }
    public function show(Payslip $payslip)
    {
        return view('personnel::admin.payslip.show', compact('payslip'));
    }
}
