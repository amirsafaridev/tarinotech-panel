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
        Schema::create('project_webs', function (Blueprint $table) {
            $table->id();
            $table->string('field_activity')->nullable();
            $table->unsignedInteger('package_id');
            $table->unsignedTinyInteger('project_type_id');
            $table->unsignedTinyInteger('pages')->default(0);

            $table->json('domains');
            $table->json('host');
            $table->json('language');
            $table->json('sample');
            $table->json('facilities');

            $table->date('agreement_at');
            $table->unsignedInteger('working_days');

            $table->timestamps();

            $table->foreign('package_id')
                ->references('id')
                ->on('packages');

            $table->foreign('project_type_id')
                ->references('id')
                ->on('project_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_webs');
    }
};
