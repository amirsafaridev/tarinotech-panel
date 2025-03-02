<?php

namespace App\Domain\Jobs;

use Google\Service\PubsubLite\Resource\Admin;
use Modules\Auth\app\Notifications\SendCodeNotification;
use Modules\Auth\app\Notifications\User\SmsOtpNotification;
use Modules\User\app\Models\User;

class SendOtpJob
{
    public function handle(User|Admin $model, string $identify, string $otp): void
    {
        if (filter_var($identify, FILTER_VALIDATE_EMAIL)) {
            $model->notify(new SendCodeNotification($otp));
        } else {
            $model->notify(new SmsOtpNotification($otp));
        }
    }
}
