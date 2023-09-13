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
        Schema::create('admin_sale_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->date('start_at');
            $table->date('end_at');
            $table->unsignedDecimal('profitability')->default(0);
            $table->foreign('admin_id')
                ->references('id')
                ->on('admins');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_sale_goals');
    }
};
