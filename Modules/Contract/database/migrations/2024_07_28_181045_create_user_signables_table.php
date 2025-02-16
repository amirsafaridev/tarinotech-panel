<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Contract\app\Enums\UserSignableStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_signables', function (Blueprint $table) {
            $table->id();

            $table->morphs('target');

            $table->unsignedBigInteger('user_id');

            $table->unsignedTinyInteger('status')
                ->default(UserSignableStatus::Pending);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_signables');
    }
};
