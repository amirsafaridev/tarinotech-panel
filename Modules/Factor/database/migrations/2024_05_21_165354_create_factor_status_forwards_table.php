<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Factor\app\Enums\FactorStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factor_status_forwards', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('factor_status')->default(FactorStatus::Pending);
            $table->unsignedInteger('project_status_id');
            $table->unsignedInteger('project_status_forward_id');
            $table->timestamps();

            $table->foreign('project_status_id')
                ->references('id')
                ->on('project_statuses');

            $table->foreign('project_status_forward_id')
                ->references('id')
                ->on('project_statuses');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factor_status_forwards');
    }
};
