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
            $table->after('base_id', function (Blueprint $table) {
                $table->unsignedTinyInteger('status_id')
                    ->nullable();
            });

            $table->foreign('status_id')
                ->on('project_statuses')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('projects_status_id_foreign');
            $table->dropColumn('status_id');
        });
    }
};
