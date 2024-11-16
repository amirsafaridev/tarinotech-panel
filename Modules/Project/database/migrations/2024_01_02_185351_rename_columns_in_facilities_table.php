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
        Schema::table('facilities', function (Blueprint $table) {

            $table->dropForeignSafe('facilities_project_base_id_foreign');

            $table->dropColumn('project_base_id');

            $table->unsignedTinyInteger('base_id');

            $table->foreign('base_id')
                ->references('id')
                ->on('project_bases');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropForeignSafe('facilities_base_id_foreign');

            $table->dropColumn('base_id');

            $table->unsignedTinyInteger('project_base_id');
        });
    }
};
