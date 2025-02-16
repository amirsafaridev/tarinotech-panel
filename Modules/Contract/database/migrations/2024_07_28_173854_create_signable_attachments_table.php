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
        Schema::create('signable_attachments', function (Blueprint $table) {
            $table->ulid();
            $table->nullableMorphs('target');

            $table->char('file_type');
            $table->char('file_extension');
            $table->unsignedInteger('file_size');
            $table->char('file_path');

            $table->boolean('is_used')
                ->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signable_attachments');
    }
};
