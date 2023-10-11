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
            $table->after('price', function (Blueprint $table) {
                $table->unsignedTinyInteger('project_status_id')
                    ->nullable();
            });

            $table->foreign('project_status_id')
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
            $table->dropForeign('project_status_id');
            $table->dropColumn('project_status_id');
        });
    }
};
