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
        Schema::create('survey_answer_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('survey_answers')->onDelete('cascade');
            $table->foreignId('survey_question_option_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // For efficient option counting
            $table->index(['survey_question_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answer_options');
    }
};
