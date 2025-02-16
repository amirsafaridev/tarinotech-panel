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
        Schema::create('factor_cheques', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('amount');
            $table->date('payment_date');
            $table->string('cheque_identifier')->nullable();
            $table->string('cheque_file')->nullable();
            $table->boolean('cheque_registered')->default(false);

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
        Schema::dropIfExists('factor_cheques');
    }
};
