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
        Schema::create('project_options', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title');
            $table->unsignedTinyInteger('base_id');
            $table->timestamps();

            $table->foreign('base_id')
                ->on('project_bases')
                ->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_options');
    }
};
