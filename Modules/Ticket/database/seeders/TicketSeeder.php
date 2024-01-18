<?php

namespace Modules\Ticket\database\seeders;

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use Illuminate\Database\Seeder;
use Modules\Project\app\Models\Project;
use Modules\Support\app\Models\Chat;
use Modules\Support\app\Models\ChatMeta;
use Modules\Support\app\Models\ChatUser;
use Modules\User\app\Models\User;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $project = Project::query()->first();

        for ($i = 1; $i < 5; $i++) {
            $chat = Chat::query()->create([
                'title' => 'تیکت شماره : '.$i,
                'logo' => 'logo.png',
                'project_id' => $project->id,
                'type' => ChatType::Ticket,
                'status' => ChatStatus::Open,
            ]);

            ChatMeta::query()
                ->create([
                    'chat_id' => $chat->id,
                    'rate' => 0,
                ]);

            ChatUser::query()
                ->create([
                    'user_type' => User::class,
                    'user_id' => $project->user_id,
                    'chat_id' => $chat->id,
                    'unread' => 0,
                ]);
        }
    }
}
