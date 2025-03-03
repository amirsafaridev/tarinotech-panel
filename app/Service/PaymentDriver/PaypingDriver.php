<?php

namespace App\Service\PaymentDriver;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Log;
use Shetabit\Multipay\Abstracts\Driver;
use Shetabit\Multipay\Contracts\ReceiptInterface;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Exceptions\PurchaseFailedException;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Receipt;
use Shetabit\Multipay\RedirectionForm;
use Shetabit\Multipay\Request;

class PaypingDriver extends Driver
{
    /**
     * Payping Client.
     *
     * @var object
     */
    protected $client;

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

    /**
     * Payping constructor.
     * Construct the class with the relevant settings.
     */
    public function __construct(Invoice $invoice, $settings)
    {
        $this->invoice($invoice);
        $this->settings = (object) $settings;

        // Create a handler stack with the default handler
        $stack = HandlerStack::create();

        // Add a middleware that logs requests and responses
        $stack->push(Middleware::log(
            Log::channel('payment'),
            new MessageFormatter(
                "==== PAYPING REQUEST ====\nMethod: {method}\nURL: {uri}\nHeaders: {req_headers}\nBody: {req_body}\n".
                "==== PAYPING RESPONSE ====\nStatus: {code}\nHeaders: {res_headers}\nBody: {res_body}\n==== END PAYPING ====\n"
            )
        ));

        // Create the client with our custom handler
        $this->client = new Client(['handler' => $stack]);
    }

    /**
     * Retrieve data from details using its name.
     *
     * @return string
     */
    private function extractDetails($name)
    {
        return empty($this->invoice->getDetails()[$name]) ? null : $this->invoice->getDetails()[$name];
    }

    /**
     * Purchase Invoice.
     *
     * @return string
     *
     * @throws PurchaseFailedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function purchase()
    {
        $name = $this->extractDetails('name');
        $mobile = $this->extractDetails('mobile');
        $email = $this->extractDetails('email');
        $description = $this->extractDetails('description');

        $data = [
            'payerName' => $name,
            'amount' => $this->invoice->getAmount() / ($this->settings->currency == 'T' ? 1 : 10), // convert to toman
            'payerIdentity' => $mobile ?? $email,
            'returnUrl' => $this->settings->callbackUrl,
            'description' => $description,
            'clientRefId' => $this->invoice->getUuid(),
        ];

        $response = $this
            ->client
            ->request(
                'POST',
                $this->settings->apiPurchaseUrl,
                [
                    'json' => $data,
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'bearer '.$this->settings->merchantId,
                    ],
                    'http_errors' => false,
                ]
            );

        $responseBody = mb_strtolower($response->getBody()->getContents());
        $body = @json_decode($responseBody, true);
        $statusCode = (int) $response->getStatusCode();

        if ($statusCode !== 200) {
            // some error has happened
            $message = is_array($body) ? array_pop($body) : $this->convertStatusCodeToMessage($statusCode);

            throw new PurchaseFailedException($message);
        }

        $this->invoice->transactionId($body['code']);

        // return the transaction's id
        return $this->invoice->getTransactionId();
    }

    /**
     * Pay the Invoice
     */
    public function pay(): RedirectionForm
    {
        $payUrl = $this->settings->apiPaymentUrl.$this->invoice->getTransactionId();

        return $this->redirectWithForm($payUrl, [], 'GET');
    }

    /**
     * Verify payment
     *
     *
     * @throws InvalidPaymentException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify(): ReceiptInterface
    {
        $refId = Request::input('refid');
        $data = [
            'amount' => $this->invoice->getAmount() / ($this->settings->currency == 'T' ? 1 : 10), // convert to toman
            'refId' => $refId,
        ];

        $response = $this->client->request(
            'POST',
            $this->settings->apiVerificationUrl,
            [
                'json' => $data,
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'bearer '.$this->settings->merchantId,
                ],
                'http_errors' => false,
            ]
        );

        $responseBody = mb_strtolower($response->getBody()->getContents());
        $body = @json_decode($responseBody, true);

        $statusCode = (int) $response->getStatusCode();

        if ($statusCode !== 200) {
            $message = is_array($body) ? array_pop($body) : $this->convertStatusCodeToMessage($statusCode);

            $this->notVerified($message, $statusCode);
        }

        $receipt = $this->createReceipt($refId);

        $receipt->detail([
            'cardNumber' => $body['cardnumber'],
        ]);

        return $receipt;
    }

    /**
     * Generate the payment's receipt
     *
     *
     * @return Receipt
     */
    protected function createReceipt($referenceId)
    {
        $receipt = new Receipt('payping', $referenceId);

        return $receipt;
    }

    /**
     * Trigger an exception
     *
     *
     * @throws InvalidPaymentException
     */
    private function notVerified($message, $status)
    {
        throw new InvalidPaymentException($message, (int) $status);
    }

    /**
     * Retrieve related message to given status code
     */
    private function convertStatusCodeToMessage(int $statusCode): string
    {
        $messages = [
            400 => 'مشکلی در ارسال درخواست وجود دارد',
            401 => 'عدم دسترسی',
            403 => 'دسترسی غیر مجاز',
            404 => 'آیتم درخواستی مورد نظر موجود نمی باشد',
            500 => 'مشکلی در سرور درگاه پرداخت رخ داده است',
            503 => 'سرور درگاه پرداخت در حال حاضر قادر به پاسخگویی نمی باشد',
        ];

        $unknown = 'خطای ناشناخته ای در درگاه پرداخت رخ داده است';

        return $messages[$statusCode] ?? $unknown;
    }
}
