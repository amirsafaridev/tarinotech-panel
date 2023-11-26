<?php

use App\Enums\Database\Chat\AttachmentType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_message_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_message_id');
            $table->unsignedInteger('type')->default(AttachmentType::Voice);
            $table->char('file_type');
            $table->char('file_extension');
            $table->unsignedInteger('file_size');
            $table->char('file_path');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('chat_message_id')
                ->references('id')
                ->on('chat_messages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_message_attachments');
    }
};
