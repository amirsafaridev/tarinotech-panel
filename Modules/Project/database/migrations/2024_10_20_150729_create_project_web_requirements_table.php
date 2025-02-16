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
        Schema::create('project_web_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_web_id');
            $table->string('representative');
            $table->string('color_scheme');
            $table->text('similar_websites');
            $table->text('preferred_websites');
            $table->string('site_title');
            $table->string('design_based_on');
            $table->text('menu_titles');
            $table->text('homepage_layout');
            $table->text('website_features');
            $table->json('internal_pages_content');
            $table->json('contract_differences');
            $table->boolean('final_decision');
            $table->timestamps();

            $table->foreign('project_web_id')
                ->references('id')
                ->on('project_webs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_web_requirements');
    }
};
