<?php
namespace Modules\Personnel\Services;

use Modules\Admin\app\Models\Admin;
use Modules\Personnel\app\Models\Payslip;

class PayslipService
{
    public function generatePayslips()
    {
        $users = Admin::where('is_block', 0)->get(); 
        foreach ($users as $user) {
            Payslip::create([
                'user_id' => $user->id,
                'status'  => 0
            ]);
        }
    }
}
