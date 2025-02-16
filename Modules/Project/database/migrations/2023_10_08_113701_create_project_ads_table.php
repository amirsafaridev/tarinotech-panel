<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Project\app\Enums\ProjectDesignBy;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_ads', function (Blueprint $table) {
            $table->id();
            $table->string('field_activity')->nullable();
            $table->unsignedTinyInteger('designed_by')
                ->default(ProjectDesignBy::ByCompany);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_ads');
    }
};
