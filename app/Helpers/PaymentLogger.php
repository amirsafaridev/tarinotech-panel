<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Log;

class PaymentLogger
{
    /**
     * Create a Guzzle client with logging enabled
     */
    public static function createLoggingClient(): Client
    {
        // Create a handler stack
        $stack = HandlerStack::create();

        // Add a middleware that logs requests and responses
        $stack->push(Middleware::log(
            Log::channel('payment'),
            new MessageFormatter(
                "REQUEST: {method} {uri}\n".
                "REQUEST HEADERS: {req_headers}\n".
                "REQUEST BODY: {req_body}\n".
                "RESPONSE STATUS: {code}\n".
                "RESPONSE HEADERS: {res_headers}\n".
                "RESPONSE BODY: {res_body}\n"
            )
        ));

        // Return a new client with the custom handler stack
        return new Client(['handler' => $stack]);
    }
}
