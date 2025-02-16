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
        Schema::create('factor_manual_infos', function (Blueprint $table) {
            $table->id();
            $table->date('payment_date');
            $table->string('file')->nullable();

            $table->unsignedBigInteger('factor_id');

            $table->timestamps();

            $table->foreign('factor_id')
                ->references('id')
                ->on('factors');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factor_manual_infos');
    }
};
