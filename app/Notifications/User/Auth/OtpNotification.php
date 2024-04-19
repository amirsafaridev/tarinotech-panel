<?php

namespace App\Notifications\User\Auth;

use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
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
