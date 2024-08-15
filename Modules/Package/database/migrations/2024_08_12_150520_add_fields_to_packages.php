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
        Schema::table('packages', function (Blueprint $table) {
            $table->after('contract_attachment', function (Blueprint $table) {
                $table->unsignedDecimal('minimum_price_percent')->default(0);
                $table->unsignedInteger('seo_keywords_count')->default(0);
                $table->unsignedInteger('seo_agreement_duration')->default(0);
                $table->unsignedInteger('seo_amount_content')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('minimum_price_percent');
            $table->dropColumn('seo_keywords_count');
            $table->dropColumn('seo_agreement_duration');
            $table->dropColumn('seo_amount_content');
        });
    }
};
