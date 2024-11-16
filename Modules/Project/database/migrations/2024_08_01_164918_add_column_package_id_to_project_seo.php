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
        Schema::table('project_seo', function (Blueprint $table) {
            $table->after('id', function (Blueprint $table) {
                $table->unsignedInteger('package_id')
                    ->nullable();

                $table->foreign('package_id')
                    ->references('id')
                    ->on('packages');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_seo', function (Blueprint $table) {
            $table->dropForeignSafe('package_id');
            $table->dropColumn('package_id');
        });
    }
};
