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
        Schema::table('project_facilities', function (Blueprint $table) {
            $table->after('facility_id', function (Blueprint $table) {
               $table->foreignId('user_id')->nullable()->constrained('admins')->onUpdate('cascade')->onDelete('cascade');
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
