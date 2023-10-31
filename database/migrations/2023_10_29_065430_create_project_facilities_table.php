<?php

use App\Enums\Database\Facility\FacilityFinancialCycle;
use App\Enums\Database\Facility\FacilityPriceType;
use App\Enums\Database\Facility\FacilityStatus;
use App\Enums\Database\Facility\FacilityWorkCycle;
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
        Schema::create('project_facilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedInteger('facility_id');

            $table->unsignedTinyInteger('price_type')->default(FacilityPriceType::None);
            $table->unsignedInteger('price_value')->default(0);

            $table->unsignedTinyInteger('work_cycle')->default(FacilityWorkCycle::None);
            $table->date('work_cycle_value')->nullable();

            $table->unsignedTinyInteger('financial_cycle')->default(FacilityFinancialCycle::None);
            $table->unsignedInteger('financial_cycle_value')->default(0);

            $table->unsignedTinyInteger('status')->default(FacilityStatus::Renewal);

            $table->date('renewal_at')->nullable();
            $table->date('added_at');

            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects');

            $table->foreign('facility_id')
                ->references('id')
                ->on('facilities');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_facilities');
    }
};
