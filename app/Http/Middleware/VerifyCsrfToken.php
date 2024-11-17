<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * Create a new middleware instance.
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        parent::__construct($app, $encrypter);

        $adminPrefix = config('routes.admin-prefix', 'admin');

        $this->except = array_merge($this->except, [
            "$adminPrefix/chat/message",
            "$adminPrefix/chat/close",
            "$adminPrefix/*/goal",
            "$adminPrefix/group-goal",
            "$adminPrefix/ajax/*",
            'ajax/*',
            'broadcasting/auth/web',
            'payment/verify/sepehr',
            'payment/verify-payping',
        ]);
    }
}
