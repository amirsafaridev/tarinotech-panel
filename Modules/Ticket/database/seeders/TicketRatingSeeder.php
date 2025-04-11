<?php

namespace Modules\Ticket\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Support\app\Models\Chat;
use Modules\Ticket\app\Models\TicketRating;
use Modules\User\app\Models\User;

class TicketRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only seed if we have ticket chats and users
        $ticketChats = Chat::where('type', 'ticket')->get();
        $users = User::all();

        if ($ticketChats->isEmpty() || $users->isEmpty()) {
            return;
        }

        // Create ratings for a subset of tickets
        foreach ($ticketChats->random(min(10, $ticketChats->count())) as $chat) {
            // Skip if chat already has a rating
            if (TicketRating::where('chat_id', $chat->id)->exists()) {
                continue;
            }

            $user = $users->random();

            TicketRating::create([
                'chat_id' => $chat->id,
                'user_type' => User::class,
                'user_id' => $user->id,
                'rating' => rand(1, 5),
                'comment' => rand(0, 1) ? $this->generateComment(rand(1, 5)) : null,
            ]);
        }
    }

    /**
     * Generate a relevant comment based on rating.
     */
    private function generateComment(int $rating): string
    {
        $excellentComments = [
            'بسیار عالی! از پشتیبانی سریع و راهنمایی‌های دقیق شما سپاسگزارم.',
            'از نحوه رسیدگی به مشکلم کاملاً راضی هستم. خیلی ممنون!',
            'پشتیبانی فوق‌العاده بود. مشکل سریعاً حل شد.',
            'برخورد پشتیبان بسیار حرفه‌ای بود. از همکاری شما بسیار متشکرم.',
            'بهترین پشتیبانی که تا به حال دریافت کرده‌ام. حتماً به دوستانم معرفی می‌کنم!',
        ];

        $goodComments = [
            'راضی هستم. پاسخگویی خوب بود.',
            'مشکلم حل شد، ممنون از توجه شما.',
            'برخورد خوب و پاسخگویی مناسب. متشکرم.',
            'راهنمایی خوبی دریافت کردم. ممنون از وقتی که گذاشتید.',
        ];

        $averageComments = [
            'نسبتاً راضی هستم اما می‌توانست سریع‌تر باشد.',
            'مشکل حل شد اما کمی زمان برد.',
            'پاسخگویی متوسط بود.',
            'در کل قابل قبول بود اما جای پیشرفت دارد.',
        ];

        $poorComments = [
            'زمان پاسخگویی خیلی طولانی بود.',
            'اطلاعات ارائه شده کافی نبود.',
            'انتظار پشتیبانی بهتری داشتم.',
            'مشکلم کاملاً حل نشد.',
        ];

        $veryPoorComments = [
            'اصلاً راضی نیستم. پاسخگویی بسیار ضعیف بود.',
            'مشکلم هنوز پابرجاست. پشتیبانی به هیچ وجه کمکی نکرد.',
            'وقتم تلف شد و هیچ راه حلی ارائه نشد.',
            'بسیار ناراضی هستم. شاید از خدمات دیگری استفاده کنم.',
        ];

        if ($rating == 5) {
            return $excellentComments[array_rand($excellentComments)];
        } elseif ($rating == 4) {
            return $goodComments[array_rand($goodComments)];
        } elseif ($rating == 3) {
            return $averageComments[array_rand($averageComments)];
        } elseif ($rating == 2) {
            return $poorComments[array_rand($poorComments)];
        } else {
            return $veryPoorComments[array_rand($veryPoorComments)];
        }
    }
}
