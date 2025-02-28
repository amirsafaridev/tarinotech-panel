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
        Schema::create('variable_amounts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('base_units_count');
            $table->string('extra_units_amount');
            $table->string('performance_amount');
            $table->string('reward_basis');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variable_amounts');
    }
};
