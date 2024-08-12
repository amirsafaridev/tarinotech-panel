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
                $table->unsignedInteger('min_contract_price')->default(0);
                $table->unsignedTinyInteger('seo_keywords_count')->default(0);
                $table->unsignedInteger('seo_agreement_duration')->default(0);
                $table->string('seo_amount_content')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('min_contract_price');
            $table->dropColumn('seo_keywords_count');
            $table->dropColumn('seo_agreement_duration');
            $table->dropColumn('seo_amount_content');
        });
    }
};
