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
        Schema::table('factor_items', function (Blueprint $table) {
            $table->bigInteger('price')->unsigned()->change();
            $table->bigInteger('final_price')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factor_items', function (Blueprint $table) {
            $table->integer('price')->unsigned()->change();
            $table->integer('final_price')->unsigned()->change();
        });
    }
};
