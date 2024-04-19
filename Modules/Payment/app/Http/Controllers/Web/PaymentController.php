<?php

namespace Modules\Payment\app\Http\Controllers\Web;

use App\Enums\Database\Factor\FactorStatus;
use App\Http\Controllers\Controller;
use Exception;
use Modules\Factor\app\Enums\PaymentGateway;
use Modules\Factor\app\Models\Factor;
use Modules\User\app\Enums\PersonType;
use Modules\User\app\Models\User;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pay($identify)
    {
        try {
            $factor = Factor::query()
                ->with(['items', 'project.user'])
                ->whereHas('project.user')
                ->where('identify', $identify)
                ->where('status', FactorStatus::Pending)
                ->first();

            if (! $factor) {
                return redirect(route('factor.factor.index', $identify))
                    ->with([
                        'message' => 'فاکتور انتخاب شده قابل پرداخت نمی باشد.',
                        'warning' => true,
                    ]);
            }

            $invoice = new Invoice();
            $invoice->amount($factor->final_price);

            $invoice->uuid($factor->identify);

            foreach ($factor->items as $item) {
                $invoice->detail($item->title, $item->price);
            }

            $paymentDriver = $this->getPaymentDriverByPersonType($factor->project->user);

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
            return $exception->getMessage();
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
                ->firstOrFail();

            Payment::via($factor->gateway)->amount($factor->final_price)
                ->transactionId($factor->transaction_id)
                ->verify();

            $factor->update([
                'gateway_data' => request()->post(),
                'status' => FactorStatus::Paid,
                'paid_at' => now(),
            ]);

            return view('payment::web.sepehr-success', compact('title', 'factor'));

        } catch (Exception $exception) {

            report($exception);
            $message = $exception->getMessage();

            return view('payment::web.message', compact('title', 'message'));

        }

    }

    public function verifyPayping()
    {
        $title = 'نتیجه تراکنش';

        try {
            $identify = request('refid');

            $factor = Factor::query()
                ->where('identify', $identify)
                ->where('status', FactorStatus::Pending)
                ->firstOrFail();

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
            $message = $exception->getMessage();

            return view('payment::web.message', compact('title', 'message'));

        }

    }

    private function getPaymentDriverByPersonType(User $user): array
    {
        if ($user->person_type === PersonType::Legal || $user->official_bill) {
            return [
                'driver' => 'sepehr',
                'verify' => route('payment.verify-sepehr'),
                'gateway' => PaymentGateway::SEPEHR,
            ];
        } else {
            return [
                'driver' => 'payping',
                'verify' => route('payment.verify-payping'),
                'gateway' => PaymentGateway::PAYPING,
            ];
        }
    }

    private function getPaymentDriver(int $getaway)
    {
        if ($getaway == PaymentGateway::PAYPING) {
            return 'payping';
        }

        return 'sepehr';
    }
}
