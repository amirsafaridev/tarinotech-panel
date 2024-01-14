<?php

namespace Modules\Support\database\seeders;

use App\Enums\Database\SampleMessage\MessageType;
use Illuminate\Database\Seeder;
use Modules\Support\app\Models\SampleMessage;

use function now;

class SampleMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'title' => 'پرسش عمومی',
                'message' => 'امیدوارم این پیام شما را در وضعیت خوبی بیابد. من یک مشتری از [نام شرکت میزبانی] هستم و درخواستی عمومی دارم که خوشحال می‌شوم اگر می‌توانید در این مورد به من کمک کنید: 1. گزینه‌ها و روش‌های پرداخت صورتحساب. 2. طرح‌های میزبانی موجود و ویژگی‌های آن‌ها. 3. هر گونه تعمیر و نگهداری مرتبط با سرورها در آینده. از کمک و پاسخ شما سپاسگزارم.',
            ],
            [
                'title' => 'مشکلات ورود به حساب کاربری',
                'message' => 'سلام به تیم پشتیبانی [نام شرکت میزبانی] عزیز، من با مشکلاتی در ورود به حساب کاربری خود روبرو شده‌ام. به صورت دقیق نام کاربری و رمز عبور خود را چک کرده‌ام، اما همچنان قادر به ورود به حساب نیستم. لطفاً به من کمک کنید تا این مشکل را به اسرع وقت حل کنم. نام کاربری حساب من: [نام کاربری شما] از کمک شما سپاسگزارم.',
            ],
            [
                'title' => 'گزارش قطعی وبسایت',
                'message' => 'با سلام به تیم پشتیبانی [نام شرکت میزبانی] عزیز، متاسفانه وبسایت من در حال حاضر در دسترس نیست و قطعی تجربه می‌شود. آیا می‌توانید این موضوع را بررسی کرده و به من اطلاعاتی در مورد علت و راه‌حل این قطعی را ارائه دهید؟ وبسایت من بسیار مهم است و نیاز به کمک شما دارم تا این مشکل را برطرف کنم. آدرس وبسایت من: [آدرس وبسایت] متشکرم که در این مورد به من کمک می‌کنید.',
            ],
        ];

        $dataToInsert = [];

        foreach ($messages as $message) {
            $dataToInsert[] = [
                'title' => $message['title'],
                'message' => $message['message'],
                'type' => MessageType::ReadyMessage,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        SampleMessage::query()->insert($dataToInsert);
    }
}
