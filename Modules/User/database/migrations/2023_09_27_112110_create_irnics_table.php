<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\User\app\Enums\IrnicStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('irnics', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->default(IrnicStatus::HasIt);
            $table->string('identify');
            $table->string('password');
            $table->unsignedBigInteger('user_id');

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
        Schema::dropIfExists('irnics');
    }
};
