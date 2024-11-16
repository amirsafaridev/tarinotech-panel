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
        Schema::table('users', function (Blueprint $table) {
            $table->after('email', function (Blueprint $table) {
                $table->string('knowledge_way')->nullable();
                $table->unsignedInteger('knowledge_way_id')->nullable();
                $table->foreign('knowledge_way_id')
                    ->references('id')
                    ->on('knowledge_ways');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignSafe('users_knowledge_way_id_foreign');
            $table->dropColumn('knowledge_way_id');
            $table->dropColumn('knowledge_way');
        });
    }
};
