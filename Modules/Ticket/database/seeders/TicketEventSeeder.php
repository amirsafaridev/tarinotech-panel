<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\app\Enums\TriggerEnum;
use Modules\Ticket\app\Models\TicketEvent;

class TicketEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'name' => 'ایجاد تیکت جدید',
                'description' => 'زمانی که یک تیکت جدید توسط کاربر ایجاد می‌شود',
                'trigger' => TriggerEnum::TICKET_CREATED,
            ],
            [
                'name' => 'پاسخ به تیکت توسط مشتری',
                'description' => 'زمانی که کاربر به تیکت پاسخ می‌دهد',
                'trigger' => TriggerEnum::CUSTOMER_REPLIED,
            ],
            [
                'name' => 'پاسخ به تیکت توسط پشتیبان',
                'description' => 'زمانی که پشتیبان به تیکت پاسخ می‌دهد',
                'trigger' => TriggerEnum::SUPPORT_REPLIED,
            ],
            [
                'name' => 'باز کردن تیکت توسط پشتیبان',
                'description' => 'زمانی که پشتیبان یک تیکت را باز می‌کند',
                'trigger' => TriggerEnum::SUPPORT_OPENED_TICKET,
            ],
            [
                'name' => 'تایم‌اوت پاسخ مشتری',
                'description' => 'یادآوری تیکت‌های بدون پاسخ از سمت مشتری',
                'trigger' => TriggerEnum::CUSTOMER_RESPONSE_TIMEOUT,
            ],
            [
                'name' => 'تایم‌اوت تایید حل مشکل',
                'description' => 'بعد از اعلام حل مشکل توسط پشتیبان و عدم تایید از سمت مشتری',
                'trigger' => TriggerEnum::RESOLUTION_CONFIRMATION_TIMEOUT,
            ],
        ];

        foreach ($events as $event) {
            TicketEvent::create($event);
        }
    }
}
