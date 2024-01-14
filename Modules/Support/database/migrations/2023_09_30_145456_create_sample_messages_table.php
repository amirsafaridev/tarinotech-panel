<?php

use App\Enums\Database\SampleMessage\MessageType;
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
        Schema::create('sample_messages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedTinyInteger('type')->default(MessageType::ReadyMessage);
            $table->text('message');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sample_messages');
    }
};
