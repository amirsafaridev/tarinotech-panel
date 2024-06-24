<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //'api/*',
        'admin/chat/message',
        'admin/chat/close',
        'admin/*/goal',
        'admin/group-goal',
        'admin/ajax/*',
        'ajax/*',
        'broadcasting/auth/web',
        'payment/verify/sepehr',
        'payment/verify-payping',
    ];
}
