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
        Schema::create('factor_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('factor_id');
            $table->string('title');
            $table->unsignedInteger('transaction_category_id');
            $table->unsignedInteger('price');
            $table->unsignedInteger('tax');
            $table->unsignedInteger('discount');
            $table->unsignedInteger('tax_amount');
            $table->unsignedInteger('final_price');

            $table->foreign('transaction_category_id')
                ->references('id')
                ->on('transaction_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factor_items');
    }
};
