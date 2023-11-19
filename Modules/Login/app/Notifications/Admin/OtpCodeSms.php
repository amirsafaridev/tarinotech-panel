<?php

namespace Modules\Login\app\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OtpCodeSms extends Notification
{
    use Queueable;

    public string $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['sms'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toSms(object $notifiable)
    {

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
