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
        Schema::table('projects', function (Blueprint $table) {
            $table->after('status_id', function (Blueprint $table) {
                $table->unsignedInteger('business_domain_id')->nullable();
                $table->string('business_domain')->nullable();
                $table->foreign('business_domain_id')
                    ->references('id')
                    ->on('business_domains');
            });
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeignSafe('projects_business_domain_id_foreign');
            $table->dropColumn('business_domain_id');
            $table->dropColumn('business_domain');
        });
    }
};
