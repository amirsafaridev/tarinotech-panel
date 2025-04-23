<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\app\Models\TicketStatus;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'در انتظار بررسی',
                'color' => '#FFC107', // yellow
                'description' => 'تیکت جدیدی که ثبت شده اما هنوز توسط کارشناسی بررسی نشده است',
                'order' => 1,
            ],
            [
                'name' => 'در حال بررسی',
                'color' => '#2196F3', // blue
                'description' => 'تیکت توسط تیم پشتیبانی بررسی شده و در حال پیگیری است',
                'order' => 2,
            ],
            [
                'name' => 'منتظر پاسخ مشتری',
                'color' => '#FF9800', // orange
                'description' => 'تیم پشتیبانی نیاز به اطلاعات یا تأیید از طرف مشتری دارد',
                'order' => 3,
            ],
            [
                'name' => 'منتظر پاسخ پشتیبانی',
                'color' => '#03A9F4', // light blue
                'description' => 'مشتری پاسخ داده و منتظر بررسی مجدد تیم پشتیبانی است',
                'order' => 4,
            ],
            [
                'name' => 'ارجاع شده',
                'color' => '#9C27B0', // purple
                'description' => 'تیکت به بخش فنی، مالی یا مدیر ارجاع داده شده است',
                'order' => 5,
            ],
            [
                'name' => 'حل شده',
                'color' => '#4CAF50', // green
                'description' => 'مشکل برطرف شده و تیکت بسته شده است (در صورت تأیید مشتری)',
                'order' => 6,
            ],
            [
                'name' => 'بسته شده',
                'color' => '#607D8B', // grey
                'description' => 'تیکت بسته شده و دیگر نیازی به پیگیری ندارد (پس از گذشت مدت مشخصی بدون پاسخ از مشتری)',
                'order' => 7,
            ],
            [
                'name' => 'لغو شده',
                'color' => '#F44336', // red
                'description' => 'تیکت توسط مشتری یا تیم پشتیبانی بدون نیاز به حل شدن بسته شده است',
                'order' => 8,
            ],
        ];

        foreach ($statuses as $status) {
            TicketStatus::create($status);
        }
    }
}
