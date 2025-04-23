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
        Schema::create('ticket_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_status_id')->constrained('ticket_statuses')->onDelete('cascade');
            $table->foreignId('to_status_id')->constrained('ticket_statuses')->onDelete('cascade');
            $table->foreignId('event_id')->constrained('ticket_events')->onDelete('cascade');
            $table->integer('days_trigger')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_transitions');
    }
};
