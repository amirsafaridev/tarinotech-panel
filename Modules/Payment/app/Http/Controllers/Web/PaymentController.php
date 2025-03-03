<?php

namespace Modules\Payment\app\Http\Controllers\Web;

use App\Domain\Jobs\FactorSerialUpdateJob;
use App\Http\Controllers\Controller;
use Exception;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Factor\app\Models\Factor;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Models\User;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class PaymentController extends Controller
{
    const FACTOR_NOT_FOUND = 'فاکتور مورد نظر یافت نشد!';

    const PAY_EXCEPTION = 'خطایی در عملیات پرداخت رخ داده است لطفا مجدد تلاش کنید!';

    /**
     * Display a listing of the resource.
     */
    public function pay($identify)
    {
        try {
            $factor = Factor::query()
                ->with(['items', 'project.user'])
                ->where('identify', $identify)
                ->where('status', FactorStatus::Pending)
                ->first();

            if (! $factor) {
                return redirect(route('factor.factor.index', $identify))
                    ->with([
                        'message' => self::FACTOR_NOT_FOUND,
                        'warning' => true,
                    ]);
            }

            $invoice = new Invoice();
            $invoice->amount($factor->final_price);

            $invoice->uuid($factor->identify);

            foreach ($factor->items as $item) {
                $invoice->detail($item->title, $item->price);
            }

            $paymentDriver = $this->getPaymentDriverByPersonType($factor->project?->user);

            return Payment::via($paymentDriver['driver'])->callbackUrl($paymentDriver['verify'])->purchase(
                $invoice,
                function ($driver, $transactionId) use ($factor, $paymentDriver) {
                    $factor->update([
                        'transaction_id' => $transactionId,
                        'gateway' => $paymentDriver['gateway'],
                    ]);
                }
            )->pay()->render();
        } catch (Exception $exception) {
            report($exception);
            $message = self::PAY_EXCEPTION;

            return view('payment::web.message', compact('message'));
        }

    }

    public function verifySepehr()
    {
        $title = 'نتیجه تراکنش';

        try {
            $identify = request('invoiceid');

            $factor = Factor::query()
                ->where('identify', $identify)
                ->where('status', FactorStatus::Pending)
                ->first();

            if (! $factor) {
                $message = self::FACTOR_NOT_FOUND;

                return view('payment::web.message', compact('message'));
            }

            Payment::via($this->getPaymentDriver($factor->gateway))
                ->amount($factor->final_price)
                ->transactionId($factor->transaction_id)
                ->verify();

            $factor->update([
                'gateway_data' => request()->post(),
                'status' => FactorStatus::Paid,
                'paid_at' => now(),
            ]);

            resolve(FactorSerialUpdateJob::class)->handle($factor);

            return view('payment::web.sepehr-success', compact('title', 'factor'));

        } catch (Exception $exception) {

            report($exception);
            $message = self::PAY_EXCEPTION;

            return view('payment::web.message', compact('message'));

        }

    }

    public function verifyPayping()
    {
        $title = 'نتیجه تراکنش';

        try {
            $identify = request('code');

            $factor = Factor::query()
                ->where('transaction_id', $identify)
                ->where('status', FactorStatus::Pending)
                ->first();

            if (! $factor) {
                $message = self::FACTOR_NOT_FOUND;

                return view('payment::web.message', compact('title', 'message'));
            }

            Payment::via($this->getPaymentDriver($factor->gateway))
                ->amount($factor->final_price)
                ->transactionId($factor->transaction_id)
                ->verify();

            $factor->update([
                'gateway_data' => request()->post(),
                'status' => FactorStatus::Paid,
                'paid_at' => now(),
            ]);

            return view('payment::web.payping-success', compact('title', 'factor'));

        } catch (Exception $exception) {

            report($exception);
            $message = self::PAY_EXCEPTION;

            return view('payment::web.message', compact('title', 'message'));

        }

    }

    private function getPaymentDriverByPersonType(?User $user): array
    {
        if ($user && ($user->person_type === PersonType::Legal || $user->official_bill)) {
            return [
                'driver' => 'sepehr',
                'verify' => route('payment.verify-sepehr'),
                'gateway' => PaymentGateway::SEPEHR,
            ];
        }

        return [
            'driver' => 'payping',
            'verify' => route('payment.verify-payping'),
            'gateway' => PaymentGateway::PAYPING,
        ];
    }

    private function getPaymentDriver(int $getaway)
    {
        if ($getaway == PaymentGateway::PAYPING) {
            return 'payping';
        }

        return 'sepehr';
    }
}
