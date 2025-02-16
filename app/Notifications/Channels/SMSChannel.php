<?php

namespace App\Notifications\Channels;

use App\Service\Sms\SMSIR;
use App\Service\Sms\SMSIRParams;
use Exception;
use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        try {
            $params = resolve(SMSIRParams::class)
                ->setParam('CODE', $notification->code);

            SMSIR::sendVerify($notifiable->mobile, 100000, $params);
        } catch (Exception $e) {
            report($e);
        }
    }
}
