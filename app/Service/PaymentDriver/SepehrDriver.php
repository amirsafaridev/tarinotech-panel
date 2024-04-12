<?php

namespace App\Service\PaymentDriver;

use Shetabit\Multipay\Abstracts\Driver;
use Shetabit\Multipay\Contracts\ReceiptInterface;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\PurchaseFailedException;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Receipt;
use Shetabit\Multipay\RedirectionForm;

class SepehrDriver extends Driver
{
    /**
     * Invoice
     *
     * @var Invoice
     */
    protected $invoice;

    /**
     * Driver settings
     *
     * @var object
     */
    protected $settings;

    public function __construct(Invoice $invoice, $settings)
    {
        $this->invoice($invoice);
        $this->settings = (object) $settings;
    }

    /**
     * @throws PurchaseFailedException
     */
    public function purchase(): string
    {

        $currencyMultiplier = $this->settings->currency == 'T' ? 10 : 1;
        $amountInRial = $this->invoice->getAmount() * $currencyMultiplier;

        $cellNumberParameter = '';
        if (! empty($this->invoice->getDetails()['mobile'])) {
            $cellNumberParameter = '&CellNumber='.$this->invoice->getDetails()['mobile'];
        }

        $requestData = http_build_query([
            'Amount' => $this->sanitizeInput($amountInRial),
            'callbackURL' => $this->sanitizeInput($this->settings->callbackUrl),
            'InvoiceID' => $this->sanitizeInput($this->invoice->getUuid()),
            'TerminalID' => $this->sanitizeInput($this->settings->terminalId),
            'Payload' => $this->sanitizeInput(''),
        ]).$cellNumberParameter;

        $tokenResponse = $this->makeHttpRequest('POST', $requestData, $this->settings->apiGetToken);

        if ($tokenResponse === false) {
            throw new PurchaseFailedException('The payment gateway is not responding, please try again later.');
        }

        $tokenData = json_decode($tokenResponse);

        if (empty($tokenData->AccessToken) && $tokenData->Status != 0) {
            $this->purchaseFailure($tokenData->Status);
        }

        $this->invoice->transactionId($tokenData->AccessToken);

        return $this->invoice->getTransactionId();
    }

    public function pay(): RedirectionForm
    {
        return $this->redirectWithForm($this->settings->apiPaymentUrl, [
            'token' => $this->invoice->getTransactionId(),
            'terminalID' => $this->settings->terminalId,
        ], 'POST');
    }

    /**
     * @throws InvalidPaymentException
     */
    public function verify(): ReceiptInterface
    {
        $responseCode = request('respcode');
        $amountInRial = $this->invoice->getAmount() * ($this->settings->currency == 'T' ? 10 : 1);

        if ($responseCode != 0) {
            $this->verificationFailure($responseCode);
        }

        $verificationData = http_build_query([
            'digitalreceipt' => request('digitalreceipt'),
            'Tid' => $this->settings->terminalId,
        ]);

        $verificationResponse = $this->makeHttpRequest('POST', $verificationData, $this->settings->apiVerificationUrl);
        $decodedResponse = json_decode($verificationResponse);

        if ($decodedResponse->Status == 'Ok') {
            if ($decodedResponse->ReturnId != $amountInRial) {
                throw new InvalidPaymentException('مبلغ واریز با قیمت محصول برابر نیست');
            }

            return $this->createReceipt(request('rrn'));
        } else {
            throw new InvalidPaymentException('تراکنش نا موفق بود، در صورت کسر مبلغ از حساب شما حداکثر پس از 72 ساعت مبلغ به حسابتان برمیگردد.');
        }
    }

    private function sanitizeInput($input): string
    {
        return htmlspecialchars(stripslashes(trim($input)));
    }

    protected function createReceipt($referenceId): Receipt
    {
        return new Receipt('sepehr', $referenceId);
    }

    /**
     * Triggers an exception based on a given status code.
     *
     * @param  int  $status  The status code associated with the purchase failure.
     *
     * @throws PurchaseFailedException Throws an exception with a message corresponding to the status code.
     */
    protected function purchaseFailure(int $status)
    {
        $translations = [
            -1 => 'تراکنش پیدا نشد.',
            -2 => 'عدم تطابق IP و یا بسته بودن پورت 8081',
            -3 => 'خطای عمومی - خطای Exception',
            -4 => 'امکان انجام درخواست برای این تراکنش وجود ندارد.',
            -5 => 'آدرس IP نامعتبر می‌باشد.',
            -6 => 'عدم فعال بودن سرویس برگشت تراکنش برای پذیرنده',
        ];

        $errorMessage = $translations[$status] ?? 'خطای ناشناخته ای رخ داده است.';
        throw new PurchaseFailedException($errorMessage);
    }

    /**
     * Triggers an exception based on a given status code.
     *
     * @param  int  $status  The status code related to the payment verification failure.
     *
     * @throws InvalidPaymentException Throws an exception with a specific message based on the status code.
     */
    private function verificationFailure(int $status)
    {
        $translations = [
            -1 => 'تراکنش توسط خریدار کنسل شده است.',
            -2 => 'زمان انجام تراکنش برای کاربر به پایان رسیده است.',
            -3 => 'خطای عمومی - خطای سیستمی',
            -4 => 'امکان انجام درخواست برای این تراکنش وجود ندارد.',
            -5 => 'آدرس IP نامعتبر می‌باشد.',
            -6 => 'عدم فعال بودن سرویس برگشت تراکنش برای پذیرنده',
        ];

        if (array_key_exists($status, $translations)) {
            throw new InvalidPaymentException($translations[$status], $status);
        } else {
            throw new InvalidPaymentException('خطای ناشناخته ای رخ داده است.', $status);
        }
    }

    private function makeHttpRequest($method, $payload, $url): bool|string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}
