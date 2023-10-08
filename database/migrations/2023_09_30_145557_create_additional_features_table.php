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
        Schema::create('additional_features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedTinyInteger('project_base_id');
            $table->timestamps();

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
        Schema::dropIfExists('additional_features');
    }
};
