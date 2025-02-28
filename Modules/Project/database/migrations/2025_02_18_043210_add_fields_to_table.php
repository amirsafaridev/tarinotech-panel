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
        Schema::table('project_types', function (Blueprint $table) {
            $table->after('title', function (Blueprint $table) {
                $table->text('eua_expert_price')->nullable();
            });
            $table->after('title', function (Blueprint $table) {
                $table->text('euc_expert_price')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_types', function (Blueprint $table) {
            $table->dropColumn('eua_expert_price');
            $table->dropColumn('euc_expert_price');

        });
    }
};
