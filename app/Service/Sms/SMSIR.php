<?php

namespace App\Service\Sms;

use Exception;
use Illuminate\Support\Facades\Http;

class SMSIR
{
    /**
     * @throws Exception
     */
    public static function sendVerify(string $mobile, int $templateId, SMSIRParams $params): string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
                'x-api-key' => config('services.smsir.key'),
            ])->post(config('services.smsir.apiVerify'), [
                'mobile' => $mobile,
                'templateId' => $templateId,
                'parameters' => $params->make(),
            ]);

            return $response->body();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

    }

    /**
     * @throws Exception
     */
    public static function send(array $mobiles, string $message): string
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
                'x-api-key' => config('services.smsir.key'),
            ])->post(config('services.smsir.apiBulk'), [
                'lineNumber' => config('services.smsir.lineNumber'),
                'messageText' => $message,
                'mobiles' => $mobiles,
                'sendDateTime' => null,
            ]);

            return $response->body();
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

    }
}
