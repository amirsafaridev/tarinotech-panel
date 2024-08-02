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
        Schema::table('signable_attachments', function (Blueprint $table) {
            $table->after('target_id', function (Blueprint $table) {
                $table->morphs('user');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('signable_attachments', function (Blueprint $table) {
            $table->dropMorphs('user');
        });
    }
};
