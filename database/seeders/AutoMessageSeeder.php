<?php

namespace Database\Seeders;

use App\Enums\Database\SampleMessage\MessageType;
use Illuminate\Database\Seeder;
use Modules\Support\app\Models\SampleMessage;

class AutoMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            'شروع گفتگو',
            'پایان گفتگو',
            'بعد از ۱۰ دقیقه بدون پاسخ',

        ];

        $dataToInsert = [];

        foreach ($messages as $message) {
            $dataToInsert[] = [
                'title' => $message,
                'message' => 'اماده ویرایش',
                'type' => MessageType::AutoSend,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        SampleMessage::query()->insert($dataToInsert);
    }
}
