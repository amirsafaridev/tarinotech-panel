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
        Schema::create('sale_goals', function (Blueprint $table) {
            $table->id();
            $table->date('start_at');
            $table->date('end_at');
            $table->unsignedInteger('profitability')->default(0);
            $table->unsignedDecimal('profitability_dollar')->default(0);
            $table->nullableMorphs('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_goals');
    }
};
