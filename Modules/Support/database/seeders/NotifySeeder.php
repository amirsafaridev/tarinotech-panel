<?php

namespace Modules\Support\database\seeders;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use Illuminate\Database\Seeder;
use Modules\Support\app\Models\Chat;
use Modules\User\app\Models\User;

class NotifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chat = Chat::query()->create([
            'title' => 'اطلاعیه ها',
            'logo' => 'uploads/chat/logo/default.png',
            'project_id' => null,
            'type' => ChatType::Public,
            'status' => ChatStatus::Open,
        ]);

        $chat->users()->create([
            'user_type' => User::class,
            'user_id' => 1,
            'seen_at' => now(),
            'unread' => 0,
        ]);
    }
}
