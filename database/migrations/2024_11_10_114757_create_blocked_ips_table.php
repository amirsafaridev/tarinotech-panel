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
        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('ip_address', 45)->unique();
            $table->timestamp('blocked_at')->nullable()->index();
            $table->unsignedSmallInteger('attempt_count')->default(1);
            $table->timestamp('expires_at')->nullable()->index();
            $table->string('reason', 255)->nullable();
            $table->timestamps();
            $table->index(['ip_address', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
    }
};
