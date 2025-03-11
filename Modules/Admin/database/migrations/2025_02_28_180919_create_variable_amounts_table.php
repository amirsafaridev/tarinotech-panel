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
        Schema::create('variable_amounts', function (Blueprint $table) {
            $table->id();
            $table->string('base_units_count');
            $table->string('extra_units_amount');
            $table->string('performance_amount');
            $table->string('reward_basis');
  $table->unsignedTinyInteger('job_title_id')->nullable();
                $table->foreign('job_title_id')
                    ->references('id')
                    ->on('job_titles')
                    ->nullOnDelete();
                                $table->date('date');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variable_amounts');
    }
};
