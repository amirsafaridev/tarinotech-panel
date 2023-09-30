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
            $table->after('title', function ($table) {
                $table->unsignedTinyInteger('project_type_id');
            });

            $table->foreign('project_type_id')
                ->on('project_types')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('project_type_id');
            $table->dropColumn('project_type_id');
        });
    }
};
