<?php

namespace App\Domain\Jobs;

use App\Helpers\Helper;
use Modules\Admin\app\Models\Admin;
use Modules\Auth\app\Models\OtpCode;
use Modules\User\app\Models\User;

class OtpGenerateJob
{
    public function handle(User|Admin $model, $identify): string
    {
        $otp = Helper::randNumeric(4);

        if (app()->isLocal() || app()->runningUnitTests()) {
            $otp = config('auth.development_otp');
        }

        OtpCode::query()->create([
            'identify' => $identify,
            'user_type' => $model::class,
            'user_id' => $model->id,
            'code' => $otp,
            'expired_at' => now()->addMinutes(5),
            'ip' => request()->ip(),
            'agent' => request()->userAgent(),
        ]);

        return $otp;
    }
}
