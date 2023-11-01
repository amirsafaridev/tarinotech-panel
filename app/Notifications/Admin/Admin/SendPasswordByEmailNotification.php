<?php

namespace App\Notifications\Admin\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPasswordByEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private string $password
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('کاربری شما ایجاد شد')
            ->line(sprintf('پرسنل عزیز (%s) کاربری شما با موفقیت ایجاد شد', $notifiable->first_name.' '.$notifiable->last_name))
            ->line(sprintf('گذرواژه شما %s می باشد برای ورود از لینک زیر استفاده کنید.', $this->password))
            ->action('صفحه ورود', route('admin.login'));
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
