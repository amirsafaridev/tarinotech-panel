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
        Schema::create('ticket_status_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_status_id')->constrained('ticket_statuses')->onDelete('cascade');
            $table->foreignId('to_status_id')->constrained('ticket_statuses')->onDelete('cascade');
            $table->integer('days_until_transition');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['from_status_id', 'to_status_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_status_transitions');
    }
};
