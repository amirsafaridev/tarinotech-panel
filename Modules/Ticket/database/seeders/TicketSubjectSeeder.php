<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Ticket\app\Models\TicketSubject;

class TicketSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'title' => 'مشکلات فنی',
                'is_published' => true,
            ],
            [
                'title' => 'سوالات حساب کاربری',
                'is_published' => true,
            ],
            [
                'title' => 'مشکلات پرداخت',
                'is_published' => true,
            ],
            [
                'title' => 'پیشنهادات و انتقادات',
                'is_published' => true,
            ],
            [
                'title' => 'همکاری با ما',
                'is_published' => true,
            ],
            [
                'title' => 'سایر موضوعات',
                'is_published' => true,
            ],
        ];

        foreach ($subjects as $subject) {
            TicketSubject::create($subject);
        }
    }
}
