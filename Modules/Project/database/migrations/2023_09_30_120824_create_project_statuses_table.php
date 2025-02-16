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
            $table->integerIncrements('id');
            $table->string('title');
            $table->unsignedTinyInteger('type_id');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('type_id')
                ->references('id')
                ->on('project_types');
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
