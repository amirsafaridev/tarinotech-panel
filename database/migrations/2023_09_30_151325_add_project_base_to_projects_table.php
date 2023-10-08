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
            $table->after('price', function ($table) {
                $table->unsignedTinyInteger('project_base_id');
            });

            $table->foreign('project_base_id')
                ->on('project_bases')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign('project_base_id');
            $table->dropColumn('project_base_id');
        });
    }
};
