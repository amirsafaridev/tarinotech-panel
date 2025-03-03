<?php

namespace App\Service\PaymentDriver;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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

        // Create a handler stack
        $stack = HandlerStack::create();

        // Add a middleware that logs requests and responses
        $stack->push(Middleware::log(
            Log::channel('payment'),
            new MessageFormatter(
                "==== PAYPING REQUEST ====\nMethod: {method}\nURL: {uri}\nHeaders: {req_headers}\nBody: {req_body}\n".
                "==== PAYPING RESPONSE ====\nStatus: {code}\nHeaders: {res_headers}\nBody: {res_body}\n==== END PAYPING ====\n"
            )
        ));

        // Initialize the Guzzle client with our custom handler stack
        $this->client = new Client([
            'handler' => $stack,
            'debug' => true, // Enable verbose cURL output
        ]);

        // Log initial driver setup
        $this->logPaymentInfo('Driver initialized', [
            'invoice_id' => $invoice->getUuid(),
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
     * @throws GuzzleException
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
            'amount' => $amount,
            'payerIdentity' => $mobile ?? $email,
            'returnUrl' => $this->settings->callbackUrl,
            'description' => $description,
            'clientRefId' => $this->invoice->getUuid(),
        ];

        // Log purchase request data
        $this->logPaymentInfo('Purchase request', [
            'url' => $this->settings->apiPurchaseUrl,
            'data' => $data,
            'headers' => [
                'Authorization' => 'bearer '.substr($this->settings->merchantId, 0, 5).'...',
            ],
        ]);

        try {
            $response = $this->client->request(
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

            // Log raw response
            $this->logPaymentInfo('Purchase response', [
                'status_code' => $statusCode,
                'body' => $body,
            ]);

            if ($statusCode !== 200) {
                // some error has happened
                $message = is_array($body) ? array_pop($body) : $this->convertStatusCodeToMessage($statusCode);

                // Log error
                $this->logPaymentInfo('Purchase failed', [
                    'status_code' => $statusCode,
                    'message' => $message,
                ], 'error');

                throw new PurchaseFailedException($message);
            }

            $this->invoice->transactionId($body['code']);

            // Log success
            $this->logPaymentInfo('Purchase successful', [
                'transaction_id' => $body['code'],
            ]);

            // return the transaction's id
            return $this->invoice->getTransactionId();

        } catch (Exception $e) {
            // Log exception
            $this->logPaymentInfo('Purchase exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 'error');

            throw $e;
        }
    }

    /**
     * Pay the Invoice
     */
    public function pay(): RedirectionForm
    {
        $payUrl = $this->settings->apiPaymentUrl.$this->invoice->getTransactionId();

        // Log payment redirect
        $this->logPaymentInfo('Payment redirect', [
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
     * @throws GuzzleException
     */
    public function verify(): ReceiptInterface
    {
        $refId = Request::input('refid');
        $amount = $this->invoice->getAmount() / ($this->settings->currency == 'T' ? 1 : 10);

        $data = [
            'amount' => $amount,
            'refId' => $refId,
        ];

        // Log verification request
        $this->logPaymentInfo('Verification request', [
            'url' => $this->settings->apiVerificationUrl,
            'data' => $data,
            'ref_id' => $refId,
        ]);

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

            // Log verification response
            $this->logPaymentInfo('Verification response', [
                'status_code' => $statusCode,
                'body' => $body,
            ]);

            if ($statusCode !== 200) {
                $message = is_array($body) ? array_pop($body) : $this->convertStatusCodeToMessage($statusCode);

                // Log verification failure
                $this->logPaymentInfo('Verification failed', [
                    'status_code' => $statusCode,
                    'message' => $message,
                ], 'error');

                $this->notVerified($message, $statusCode);
            }

            // Log verification success
            $this->logPaymentInfo('Verification successful', [
                'ref_id' => $refId,
                'card_number' => $body['cardnumber'] ?? null,
            ]);

            $receipt = $this->createReceipt($refId);

            $receipt->detail([
                'cardNumber' => $body['cardnumber'],
            ]);

            return $receipt;
        } catch (Exception $e) {
            // Log exception
            $this->logPaymentInfo('Verification exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 'error');

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
     * Log payment information to dedicated channel
     *
     * @param  string  $message  Log message
     * @param  array  $context  Log context data
     * @param  string  $level  Log level (info, error, debug)
     */
    private function logPaymentInfo(string $message, array $context = [], string $level = 'info'): void
    {
        // Add common context data to all logs
        $context = array_merge([
            'driver' => 'payping',
            'invoice_uuid' => $this->invoice->getUuid(),
            'amount' => $this->invoice->getAmount(),
            'timestamp' => date('Y-m-d H:i:s'),
        ], $context);

        // Log to the payment channel with the appropriate level
        Log::channel('payment')->$level($message, $context);
    }
}
