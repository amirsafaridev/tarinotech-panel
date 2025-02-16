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
        Schema::table('factors', function (Blueprint $table) {
            Schema::table('factors', function (Blueprint $table) {
                $table->after('is_official', function (Blueprint $table) {
                    $table->boolean('is_automate')->default(false);
                });
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factors', function (Blueprint $table) {
            $table->dropColumn('is_automate');
        });
    }
};
