<?php

namespace Modules\Personnel\app\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\HasJsonCommonResponseTrait;
use Modules\Admin\app\Models\BonusesDeduction;
use Modules\Personnel\app\Models\Payslip;
use Modules\Personnel\Services\PayslipService;
use Modules\Admin\app\Models\FixedAmount;
use Modules\Admin\app\Models\VariableAmount;
use Modules\Admin\app\Models\PersonnelSalary;
use Modules\Admin\app\Models\PersonnelAssistance;

use Modules\Admin\app\Models\PersonnelReport;
use Carbon\Carbon;


class PayslipController extends Controller
{
    use HasJsonCommonResponseTrait;

    const INDEX_TITLE = 'فیش های حقوقی';
    const CREATE_TITLE = 'فیش حقوقی - ایجاد';
    const EDIT_TITLE = 'فیش حقوقی - ویرایش';

    const MONTHLY_ALLOWED_LEAVE_HOURS = 22; // 2 روز و 2 ساعت
    const MAX_BUYBACK_DAYS = 15; // حداکثر روزهای قابل بازخرید در سال

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
        $personnelSalary = PersonnelSalary::where('user_id', $payslip->user->id)->first();
        $personnelAssistance = PersonnelAssistance::where('user_id', $payslip->user->id)->first();
        $personnelOtherDeduction = BonusesDeduction::where('type', 1)->first();
        
        // محاسبه مقادیر پورسانت برای کارشناس مشاوره و فروش
        $contractAmount = 0;
        $sitePayment = 0;
        $seoPayment = 0;
        $advancedProjects = 0;
        $economicProjects = 0;
        $currentSeoProjects = 0;
        $commission = 0;

        if ($payslip->user->jobTitle !=null&&$payslip->user->jobTitle->title === 'کارشناس مشاوره و فروش') {
            // محاسبه میزان قرارداد
            $contractAmount = $this->calculateContractAmount($payslip->user->id, $payslip->created_at);
            
            // محاسبه واریزی طراحی سایت
            $sitePayment = $this->calculateSitePayment($payslip->user->id, $payslip->created_at);
            
            // محاسبه واریزی سئو
            $seoPayment = $this->calculateSeoPayment($payslip->user->id, $payslip->created_at);
            
            // محاسبه تعداد پروژه‌ها
            $projectCounts = $this->calculateProjectCounts($payslip->user->id, $payslip->created_at);
            $advancedProjects = $projectCounts['advanced'];
            $economicProjects = $projectCounts['economic'];
            $currentSeoProjects = $projectCounts['current_seo'];

            // محاسبه پورسانت
            $commission = $this->calculateCommission(
                $sitePayment,
                $seoPayment,
                $variableAmount->base_sales,
                $variableAmount->base_payment,
                $variableAmount->min_site_commission,
                $variableAmount->max_site_commission,
                $variableAmount->seo_commission
            );
        }

        // محاسبه مجموع دریافتی‌ها
        $totalBenefits = $fixedAmount->basic_rights + 
                        $variableAmount->performance_amount + 
                        $commission + 
                        $fixedAmount->right_to_housing + 
                        ($payslip->user->is_married ? $fixedAmount->right_to_marry : 0) + 
                        $fixedAmount->right_to_eat_and_drink;

        // محاسبه مجموع کسورات
        $totalDeductions = $personnelSalary->price + 
                          $personnelAssistance->price + 
                          ($personnelOtherDeduction->price ?? 0) + 
                          ($payslip->user->work_location == 2 ? $fixedAmount->employer_insurance : 0);

        // محاسبه حقوق قابل پرداخت نهایی
        $finalSalary = $totalBenefits - $totalDeductions;

        // محاسبه وضعیت حضور و غیاب
        $startOfMonth = Carbon::parse($payslip->created_at)->startOfMonth();
        $endOfMonth = Carbon::parse($payslip->created_at)->endOfMonth();

        // محاسبه مرخصی‌های ماه
        $monthlyLeaves = PersonnelReport::where('user_id', $payslip->user->id)
            ->where('status', 'approved')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $totalLeaveHours = 0;
        foreach ($monthlyLeaves as $leave) {
            if ($leave->type === 'daily') {
                $totalLeaveHours += ($leave->end_date->diffInDays($leave->start_date) + 1) * 8;
            } else {
                $totalLeaveHours += $leave->total_hours;
            }
        }

        // محاسبه مرخصی مجاز
        $allowedLeaveHours = self::MONTHLY_ALLOWED_LEAVE_HOURS;

        // محاسبه مرخصی بیش از حد مجاز
        $excessLeaveHours = max(0, $totalLeaveHours - $allowedLeaveHours);

        // محاسبه مرخصی استفاده شده
        $usedLeaveHours = min($totalLeaveHours, $allowedLeaveHours);

        // محاسبه مرخصی ذخیره شده
        $startOfYear = Carbon::parse($payslip->created_at)->startOfYear();
        $endOfYear = Carbon::parse($payslip->created_at)->endOfYear();
        
        $yearlyLeaves = PersonnelReport::where('user_id', $payslip->user->id)
            ->where('status', 'approved')
            ->whereBetween('date', [$startOfYear, $endOfYear])
            ->get();

        $totalYearlyLeaveHours = 0;
        foreach ($yearlyLeaves as $leave) {
            if ($leave->type === 'daily') {
                $totalYearlyLeaveHours += ($leave->end_date->diffInDays($leave->start_date) + 1) * 8;
            } else {
                $totalYearlyLeaveHours += $leave->total_hours;
            }
        }

        $savedLeaveHours = max(0, ($allowedLeaveHours * 12) - $totalYearlyLeaveHours);
        $savedLeaveDays = floor($savedLeaveHours / 8);
        $savedLeaveHours = $savedLeaveHours % 8;

        // محاسبه غیبت
        $absenceHours = max(0, $excessLeaveHours);

        // محاسبه تأخیر بیش از حد مجاز
        $lateHours = 0; // این مقدار باید از سیستم حضور و غیاب محاسبه شود

        // محاسبه کارکرد ساعتی / روزانه
        $workHours = 0; // این مقدار باید از سیستم حضور و غیاب محاسبه شود

        return view('personnel::admin.payslip.show', compact(
            'payslip',
            'fixedAmount',
            'variableAmount',
            'personnelSalary',
            'personnelAssistance',
            'personnelOtherDeduction',
            'commission',
            'totalBenefits',
            'totalDeductions',
            'finalSalary',
            'contractAmount',
            'sitePayment',
            'seoPayment',
            'advancedProjects',
            'economicProjects',
            'currentSeoProjects',
            'allowedLeaveHours',
            'excessLeaveHours',
            'usedLeaveHours',
            'savedLeaveDays',
            'savedLeaveHours',
            'absenceHours',
            'lateHours',
            'workHours'
        ));
    }

    private function calculateContractAmount($userId, $date)
    {
        // TODO: این تابع باید با مدل واقعی جایگزین شود
        return 0;
    }

    private function calculateSitePayment($userId, $date)
    {
        // TODO: این تابع باید با مدل واقعی جایگزین شود
        return 0;
    }

    private function calculateSeoPayment($userId, $date)
    {
        // TODO: این تابع باید با مدل واقعی جایگزین شود
        return 0;
    }

    private function calculateProjectCounts($userId, $date)
    {
        // TODO: این تابع باید با مدل واقعی جایگزین شود
        return [
            'advanced' => 0,
            'economic' => 0,
            'current_seo' => 0
        ];
    }

    private function calculateCommission($sitePayment, $seoPayment, $baseSales, $basePayment, $minSiteCommission, $maxSiteCommission, $seoCommission)
    {
        if ($sitePayment <= $basePayment) {
            return 0;
        }

        if ($sitePayment <= 2 * $basePayment) {
            return ($minSiteCommission * ($sitePayment - $basePayment)) + ($seoCommission * $seoPayment);
        }

        return ($maxSiteCommission * ($sitePayment - 2 * $basePayment)) + 
               ($minSiteCommission * $basePayment) + 
               ($seoCommission * $seoPayment);
    }

    public function approved(Payslip $payslip)
    {
        $payslip->status = 1;
        $payslip->save();
        return $this->successBack(route('admin.personnel.payslip.index'),'فیش حقوقی مورد نظر تایید شد');
    }

    public function canceled(Payslip $payslip)
    {
        $payslip->status = 2;
        $payslip->save();
        return $this->successBack(route('admin.personnel.payslip.index'),'فیش حقوقی مورد نظر رد شد');
    }
}
