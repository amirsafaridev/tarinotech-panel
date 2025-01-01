<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Project\app\Enums\RenewalStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->bigInteger('project_price');
            $table->bigInteger('package_price');
            $table->bigInteger('package_calculated_price');
            $table->unsignedTinyInteger('status')->default(RenewalStatus::Pending);
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_renewals');
    }
};
