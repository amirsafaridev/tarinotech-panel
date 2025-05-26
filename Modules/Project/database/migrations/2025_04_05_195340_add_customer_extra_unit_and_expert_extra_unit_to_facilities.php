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
        Schema::table('facilities', function (Blueprint $table) {
            $table->after('title', function (Blueprint $table) {
                $table->string('customer_extra_unit')->nullable();
                $table->string('expert_extra_unit')->nullable();
            });
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
