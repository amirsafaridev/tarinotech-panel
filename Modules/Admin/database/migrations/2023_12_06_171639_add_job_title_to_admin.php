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
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('job_title');
            $table->after('avatar', function (Blueprint $table) {
                $table->unsignedTinyInteger('job_title_id')->nullable();
                $table->foreign('job_title_id')
                    ->references('id')
                    ->on('job_titles')
                    ->nullOnDelete();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign('job_title_id');
            $table->dropColumn('job_title_id');
            $table->after('avatar', function (Blueprint $table) {
                $table->string('job_title')->nullable();
            });
        });
    }
};
