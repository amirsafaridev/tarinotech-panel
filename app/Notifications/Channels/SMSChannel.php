<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class SMSChannel
{
    public function send($notifiable, Notification $notification)
    {
        $notification->toSMS($notifiable);
    }
}
