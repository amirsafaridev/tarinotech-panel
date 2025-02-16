<?php

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
        Schema::table('chat_message_attachments', function (Blueprint $table) {
            $table->after('file_type', function (Blueprint $table) {
                $table->string('file_name');
            });
            $table->unsignedBigInteger('chat_message_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_message_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('chat_message_id')->nullable(false)->change();
            $table->dropColumn('file_name');
        });
    }
};
