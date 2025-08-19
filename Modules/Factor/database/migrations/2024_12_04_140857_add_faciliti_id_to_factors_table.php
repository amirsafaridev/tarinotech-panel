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

        Schema::table('factors', function (Blueprint $table) {
            $table->after('project_id', function (Blueprint $table) {
            $table->unsignedInteger('facility_id')->nullable();
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');

            });
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
    }
};
