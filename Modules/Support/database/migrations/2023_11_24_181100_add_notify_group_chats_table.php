<?php

use App\Enums\Database\Chat\ChatStatus;
use App\Enums\Database\Chat\ChatType;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('chats')
            ->insert([
                'title' => 'اطلاعیه ها',
                'logo' => 'logo.png',
                'type' => ChatType::Public,
                'status' => ChatStatus::Open,
                'project_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
