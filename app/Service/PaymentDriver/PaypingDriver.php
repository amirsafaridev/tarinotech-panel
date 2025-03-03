<?php

namespace App\Service\PaymentDriver;

use GuzzleHttp\Client;
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
     * Log channel name
     *
     * @var string
     */
    protected $logChannel = 'payment';

    /**
     * Payping constructor.
     * Construct the class with the relevant settings.
     */
    public function __construct(Invoice $invoice, $settings)
    {
        $this->invoice($invoice);
        $this->settings = (object) $settings;
        $this->client = new Client();

        $this->log('info', 'Payment driver initialized', [
            'invoice_uuid' => $invoice->getUuid(),
            'amount' => $invoice->getAmount(),
            'driver' => 'payping',
        ]);
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

        $amount = $this->invoice->getAmount() / ($this->settings->currency == 'T' ? 1 : 10);

        $data = [
            'payerName' => $name,
            'amount' => $amount, // convert to toman
            'payerIdentity' => $mobile ?? $email,
            'returnUrl' => $this->settings->callbackUrl,
            'description' => $description,
            'clientRefId' => $this->invoice->getUuid(),
        ];

        $this->log('info', 'Payment purchase initiated', [
            'invoice_uuid' => $this->invoice->getUuid(),
            'amount' => $amount,
            'payer_name' => $name,
            'payer_identity' => $mobile ?? $email,
            'description' => $description,
        ]);

        try {
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

            $this->log('info', 'Payment gateway response received', [
                'invoice_uuid' => $this->invoice->getUuid(),
                'status_code' => $statusCode,
                'response_body' => $body,
            ]);

            if ($statusCode !== 200) {
                // some error has happened
                $message = is_array($body) ? array_pop($body) : $this->convertStatusCodeToMessage($statusCode);

                $this->log('error', 'Payment purchase failed', [
                    'invoice_uuid' => $this->invoice->getUuid(),
                    'status_code' => $statusCode,
                    'message' => $message,
                    'response_body' => $body,
                ]);

                throw new PurchaseFailedException($message);
            }

            $this->invoice->transactionId($body['code']);

            $this->log('info', 'Payment purchase successful', [
                'invoice_uuid' => $this->invoice->getUuid(),
                'transaction_id' => $body['code'],
            ]);

            // return the transaction's id
            return $this->invoice->getTransactionId();

        } catch (\Exception $e) {
            $this->log('error', 'Exception during payment purchase', [
                'invoice_uuid' => $this->invoice->getUuid(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Pay the Invoice
     */
    public function pay(): RedirectionForm
    {
        $payUrl = $this->settings->apiPaymentUrl.$this->invoice->getTransactionId();

        $this->log('info', 'Payment redirection initialized', [
            'invoice_uuid' => $this->invoice->getUuid(),
            'transaction_id' => $this->invoice->getTransactionId(),
            'redirect_url' => $payUrl,
        ]);

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
        $amount = $this->invoice->getAmount() / ($this->settings->currency == 'T' ? 1 : 10);

        $this->log('info', 'Payment verification initiated', [
            'invoice_uuid' => $this->invoice->getUuid(),
            'ref_id' => $refId,
            'amount' => $amount,
        ]);

        $data = [
            'amount' => $amount, // convert to toman
            'refId' => $refId,
        ];

        try {
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

            $this->log('info', 'Payment verification response received', [
                'invoice_uuid' => $this->invoice->getUuid(),
                'ref_id' => $refId,
                'status_code' => $statusCode,
                'response_body' => $body,
            ]);

            if ($statusCode !== 200) {
                $message = is_array($body) ? array_pop($body) : $this->convertStatusCodeToMessage($statusCode);

                $this->log('error', 'Payment verification failed', [
                    'invoice_uuid' => $this->invoice->getUuid(),
                    'ref_id' => $refId,
                    'status_code' => $statusCode,
                    'message' => $message,
                    'response_body' => $body,
                ]);

                $this->notVerified($message, $statusCode);
            }

            $receipt = $this->createReceipt($refId);
            $receipt->detail([
                'cardNumber' => $body['cardnumber'],
            ]);

            $this->log('info', 'Payment verification successful', [
                'invoice_uuid' => $this->invoice->getUuid(),
                'ref_id' => $refId,
                'card_number' => $body['cardnumber'] ?? 'Not provided',
            ]);

            return $receipt;

        } catch (\Exception $e) {
            $this->log('error', 'Exception during payment verification', [
                'invoice_uuid' => $this->invoice->getUuid(),
                'ref_id' => $refId,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
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

    /**
     * Log a message to the payment channel
     *
     * @return void
     */
    protected function log(string $level, string $message, array $context = [])
    {
        Log::channel($this->logChannel)->$level($message, $context);
    }
}
