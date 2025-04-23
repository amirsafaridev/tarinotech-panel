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
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('ticket_subjects')->onDelete('restrict');
            $table->foreignId('status_id')->constrained('ticket_statuses')->onDelete('restrict');
            $table->foreignId('priority_id')->constrained('ticket_priorities')->onDelete('restrict');
            $table->foreignId('assigned_to')->nullable()->constrained('admins')->onDelete('set null');
            $table->dateTime('last_response_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->dateTime('rated_at')->nullable();
            $table->text('rating_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_details');
    }
};
