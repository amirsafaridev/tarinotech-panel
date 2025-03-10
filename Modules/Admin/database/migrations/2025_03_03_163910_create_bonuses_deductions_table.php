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
        Schema::create('bonuses_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('admins')->onUpdate('cascade')->onDelete('cascade');
            $table->string('price');
            $table->tinyInteger('type')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonuses_deductions');
    }
};
