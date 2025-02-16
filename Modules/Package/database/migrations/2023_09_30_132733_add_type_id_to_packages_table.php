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
            $table->after('title', function (Blueprint $table) {

                $table->dropForeignSafe('packages_base_id_foreign');
                $table->dropColumn('base_id');

                $table->unsignedTinyInteger('type_id')->nullable();

                $table->foreign('type_id')
                    ->on('project_types')
                    ->references('id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeignSafe('packages_type_id_foreign');
            $table->dropColumn('type_id');

            $table->after('title', function (Blueprint $table) {
                $table->unsignedTinyInteger('base_id')->nullable();

                $table->foreign('base_id')
                    ->on('project_bases')
                    ->references('id');
            });
        });
    }
};
