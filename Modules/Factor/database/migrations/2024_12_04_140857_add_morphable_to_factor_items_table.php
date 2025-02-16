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
            $table->after('final_price', function (Blueprint $table) {
                $table->nullableMorphs('targetable');
            });
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factor_items', function (Blueprint $table) {
            $table->dropMorphs('targetable');
        });
    }
};
