<?php

namespace App\Http\Controllers;

use App\Service\Sms\SMSIR;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\app\Notifications\Admin\SendPasswordByEmail;
use Modules\Auth\app\Notifications\User\SmsOtpNotification;
use Modules\Package\app\Models\Package;
use Modules\Project\app\Imports\ProjectWebImport;
use Modules\User\app\Imports\UserImport;
use Modules\User\app\Models\User;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class TestController extends Controller
{
    public function index()
    {
        $this->dieInProduction();
        $startDate = '2024-08-12';

        return date('m-d', strtotime($startDate));
    }

    public function packagePrice()
    {
        $package = Package::find(1);

        return $package
            ->getPriceForDate('2024-08-12 14:25')
            ->toArray();
    }

    public function sendEmail()
    {
        $this->dieInProduction();
        $admin = Admin::query()->find(14);
        $admin->notify(new SendPasswordByEmail('1234'));
    }

    public function smsSend()
    {
        $this->dieInProduction();

        /*try {
            $params = resolve(SMSIRParams::class)
                ->setParam('123');

            return SMSIR::sendVerify('9358394242', 100000, $params);
        } catch (\Exception $e) {
            return $e->getMessage();
        }*/

        try {
            return SMSIR::send(['+989358394242'], 'این پیامک برای اطمینان از راه اندازی پنل پیامک پرتال تارینوتک می باشد.موسوی');
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function awsUpload()
    {
        // https://docs.arvancloud.ir/fa/object-storage/
        $this->dieInProduction();
        Storage::disk('s3')->put('custom.css', file_get_contents(public_path('res-admin/assets/css/custom.css')));
        $url = Storage::disk('s3')->url('custom5.css');
    }

    public function awsList()
    {
        $this->dieInProduction();
        $s3 = Storage::disk('s3');
        $objects = $s3->get('/custom.css');
    }

    public function import()
    {
        $this->dieInProduction();
        try {
            Excel::import(new UserImport(), 'Customer.xlsx', 'public');
        } catch (ValidationException $e) {
            return $e->failures();
        }
    }

    public function importProject()
    {
        $this->dieInProduction();

        try {
            Excel::import(new ProjectWebImport(), 'WebProject.xlsx', 'public');
        } catch (ValidationException $e) {
            return $e->failures();
        }
    }

    public function phpInfo()
    {
        echo phpinfo();
    }

    public function pay()
    {
        try {

            return Payment::via('sepehr')->purchase(
                (new Invoice)->amount(1000),
                function ($driver, $transactionId) {
                    // Store transactionId in database.
                    // We need the transactionId to verify payment in the future.
                }
            )->pay()->render();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function otpNotification()
    {
        $this->dieInProduction();
        $user = User::query()->first();
        $user->notify(new SmsOtpNotification('2233'));
    }
}
