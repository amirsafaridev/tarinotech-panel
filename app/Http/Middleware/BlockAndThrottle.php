<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\Uid\Ulid;

class BlockAndThrottle
{
    protected int $maxAttempts = 10;

    protected int $decayMinutes = 5;

    protected int $blockDurationMinutes = 60;

    public function handle(Request $request, Closure $next)
    {
        if (app()->isLocal()) {
            return $next($request);
        }

        $ip = $request->ip();

        // Check if the IP is blocked and unblock if expired
        if ($this->isIpBlocked($ip)) {
            if ($this->hasBlockExpired($ip)) {
                $this->unblockIp($ip);
            } else {
                // Redirect to a custom blocked view if the IP is blocked
                return redirect()->route('blocked.ip');
            }
        }

        // Check for rate limiting
        if (RateLimiter::tooManyAttempts($this->throttleKey($ip), $this->maxAttempts)) {
            // Log failed attempt and block IP
            $this->blockIp($ip);

            // Flash a message for the next request and redirect
            return redirect()->route('blocked.ip')->with('error', 'Too many attempts. Your IP has been blocked.');
        }

        RateLimiter::hit($this->throttleKey($ip), $this->decayMinutes * 60);

        return $next($request);
    }

    protected function throttleKey($ip): string
    {
        return 'admin-login-attempts:'.$ip;
    }

    protected function isIpBlocked($ip): bool
    {
        return DB::table('blocked_ips')
            ->where('ip_address', $ip)
            ->exists();
    }

    protected function hasBlockExpired($ip): bool
    {
        $blockedIp = DB::table('blocked_ips')->where('ip_address', $ip)->first();
        if ($blockedIp && $blockedIp->expires_at) {
            return Carbon::now()->greaterThanOrEqualTo($blockedIp->expires_at);
        }

        return false;
    }

    protected function blockIp($ip): void
    {
        DB::table('blocked_ips')->updateOrInsert(
            ['ip_address' => $ip],
            [
                'id' => Ulid::generate(),
                'blocked_at' => Carbon::now(),
                'attempt_count' => DB::raw('attempt_count + 1'),
                'expires_at' => Carbon::now()->addMinutes($this->blockDurationMinutes),
                'reason' => 'Too many failed login attempts',
            ]
        );

        RateLimiter::clear($this->throttleKey($ip));
    }

    protected function unblockIp($ip): void
    {
        DB::table('blocked_ips')->where('ip_address', $ip)->delete();
    }
}
