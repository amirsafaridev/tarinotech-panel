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
        Schema::create('project_statuses', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('title');
            $table->unsignedTinyInteger('project_base_id');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('project_base_id')
                ->references('id')
                ->on('project_bases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_statuses');
    }
};
