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

        try {
            // Log driver initialization
            Log::channel('payment')->info('PaypingDriver initialized', [
                'uuid' => $invoice->getUuid(),
                'amount' => $invoice->getAmount(),
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (Exception $e) {
            // Log any exception during initialization
            $this->logException('Driver initialization failed', $e);
        }
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
        try {
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

            // Log purchase attempt
            Log::channel('payment')->info('PaypingDriver purchase attempt', [
                'uuid' => $this->invoice->getUuid(),
                'amount' => $data['amount'],
                'timestamp' => now()->toDateTimeString(),
            ]);

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

            $responseBody = $response->getBody()->getContents();
            $body = @json_decode($responseBody, true);
            $statusCode = (int) $response->getStatusCode();

            if ($statusCode !== 200) {
                // some error has happened
                $message = is_array($body) ? array_pop($body) : $this->convertStatusCodeToMessage($statusCode);

                // Log the failed purchase
                Log::channel('payment')->error('PaypingDriver purchase failed', [
                    'uuid' => $this->invoice->getUuid(),
                    'status_code' => $statusCode,
                    'error_message' => $message,
                    'response_body' => $responseBody,
                    'timestamp' => now()->toDateTimeString(),
                ]);

                throw new PurchaseFailedException($message);
            }

            // Check if the code key exists in the response
            if (! isset($body['code'])) {
                $errorMessage = 'Invalid response format: missing transaction code';

                // Log the invalid response
                Log::channel('payment')->error('PaypingDriver invalid response', [
                    'uuid' => $this->invoice->getUuid(),
                    'error_message' => $errorMessage,
                    'response_body' => $responseBody,
                    'timestamp' => now()->toDateTimeString(),
                ]);

                throw new PurchaseFailedException($errorMessage);
            }

            $this->invoice->transactionId($body['code']);

            // Log successful purchase
            Log::channel('payment')->info('PaypingDriver purchase successful', [
                'uuid' => $this->invoice->getUuid(),
                'transaction_id' => $body['code'],
                'timestamp' => now()->toDateTimeString(),
            ]);

            // return the transaction's id
            return $this->invoice->getTransactionId();

        } catch (PurchaseFailedException $e) {
            // This will be caught at a higher level, just rethrow
            throw $e;
        } catch (Exception $e) {
            // Log any unexpected exceptions
            $this->logException('Unexpected exception in purchase', $e);

            // Re-throw as a PurchaseFailedException
            throw new PurchaseFailedException($e->getMessage());
        }
    }

    /**
     * Pay the Invoice
     */
    public function pay(): RedirectionForm
    {
        try {
            $payUrl = $this->settings->apiPaymentUrl.$this->invoice->getTransactionId();

            // Log the payment redirect
            Log::channel('payment')->info('PaypingDriver redirecting to payment page', [
                'uuid' => $this->invoice->getUuid(),
                'transaction_id' => $this->invoice->getTransactionId(),
                'redirect_url' => $payUrl,
                'timestamp' => now()->toDateTimeString(),
            ]);

            return $this->redirectWithForm($payUrl, [], 'GET');
        } catch (Exception $e) {
            // Log any exception
            $this->logException('Exception in payment redirect', $e);
            throw $e;
        }
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
        try {
            $refId = Request::input('refid');
            $data = [
                'amount' => $this->invoice->getAmount() / ($this->settings->currency == 'T' ? 1 : 10), // convert to toman
                'refId' => $refId,
            ];

            // Log verification attempt
            Log::channel('payment')->info('PaypingDriver verification attempt', [
                'uuid' => $this->invoice->getUuid(),
                'ref_id' => $refId,
                'amount' => $data['amount'],
                'timestamp' => now()->toDateTimeString(),
            ]);

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

                // Log verification failure
                Log::channel('payment')->error('PaypingDriver verification failed', [
                    'uuid' => $this->invoice->getUuid(),
                    'ref_id' => $refId,
                    'status_code' => $statusCode,
                    'error_message' => $message,
                    'response_body' => $responseBody,
                    'timestamp' => now()->toDateTimeString(),
                ]);

                $this->notVerified($message, $statusCode);
            }

            // Check if required response data exists
            if (! isset($body['cardnumber'])) {
                $errorMessage = 'Invalid verification response: missing card number';

                // Log the invalid response
                Log::channel('payment')->error('PaypingDriver invalid verification response', [
                    'uuid' => $this->invoice->getUuid(),
                    'ref_id' => $refId,
                    'error_message' => $errorMessage,
                    'response_body' => $responseBody,
                    'timestamp' => now()->toDateTimeString(),
                ]);

                $this->notVerified($errorMessage, 400);
            }

            // Log successful verification
            Log::channel('payment')->info('PaypingDriver verification successful', [
                'uuid' => $this->invoice->getUuid(),
                'ref_id' => $refId,
                'card_number' => substr($body['cardnumber'], 0, 6).'******'.substr($body['cardnumber'], -4), // Mask the card number
                'timestamp' => now()->toDateTimeString(),
            ]);

            $receipt = $this->createReceipt($refId);

            $receipt->detail([
                'cardNumber' => $body['cardnumber'],
            ]);

            return $receipt;
        } catch (InvalidPaymentException $e) {
            // This will be caught at a higher level, just rethrow
            throw $e;
        } catch (Exception $e) {
            // Log any unexpected exceptions
            $this->logException('Unexpected exception in verification', $e);

            // Re-throw as InvalidPaymentException
            throw new InvalidPaymentException($e->getMessage());
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
     * Log exceptions with detailed information
     *
     * @param  string  $message  The log message
     * @param  Exception  $exception  The exception to log
     */
    private function logException(string $message, Exception $exception): void
    {
        Log::channel('payment')->error('PaypingDriver exception: '.$message, [
            'uuid' => $this->invoice ? $this->invoice->getUuid() : 'unknown',
            'exception_class' => get_class($exception),
            'exception_message' => $exception->getMessage(),
            'exception_code' => $exception->getCode(),
            'exception_file' => $exception->getFile().':'.$exception->getLine(),
            'exception_trace' => $exception->getTraceAsString(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
