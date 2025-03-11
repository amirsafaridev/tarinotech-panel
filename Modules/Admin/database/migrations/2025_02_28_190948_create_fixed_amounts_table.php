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
        Schema::create('fixed_amounts', function (Blueprint $table) {
            $table->id();
            $table->string('basic_rights');
            $table->string('right_to_housing');
            $table->string('right_to_marry');
            $table->string('childrens_right');
            $table->string('right_to_eat_and_drink');
            $table->string('employer_insurance')->nullable();
            $table->string('personnel_insurance')->nullable();
            $table->string('employer_insurance_remote')->nullable();
            $table->string('personnel_insurance_remote')->nullable();
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
        Schema::dropIfExists('fixed_amounts');
    }
};
