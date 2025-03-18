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
        Schema::table('personnel_reports', function (Blueprint $table) {
            $table->boolean('is_emergency')->default(false)->after('rules_accepted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnel_reports', function (Blueprint $table) {
            $table->dropColumn('is_emergency');
        });
    }
};
