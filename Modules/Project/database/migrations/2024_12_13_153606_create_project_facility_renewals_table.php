<?php

use App\Enums\Database\Facility\FacilityStatus;
use App\Enums\Database\Facility\FinancialCycle;
use App\Enums\Database\Facility\PriceType;
use App\Enums\Database\Facility\WorkCycle;
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
        Schema::create('project_facility_renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('facility_id');
            $table->unsignedBigInteger('project_renewal_id');

            $table->unsignedTinyInteger('price_type')->default(PriceType::None);
            $table->unsignedBigInteger('price_value')->default(0);

            $table->unsignedTinyInteger('work_cycle')->default(WorkCycle::None);
            $table->date('work_cycle_value')->nullable();

            $table->unsignedTinyInteger('financial_cycle')->default(FinancialCycle::None);
            $table->unsignedBigInteger('financial_cycle_value')->default(0);

            $table->unsignedInteger('days')->default(0);

            $table->unsignedTinyInteger('status')->default(FacilityStatus::Renewal);

            $table->unsignedBigInteger('calculated_price')->default(0);

            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('facility_id')
                ->references('id')
                ->on('facilities')
                ->onDelete('cascade');

            $table->foreign('project_renewal_id')
                ->references('id')
                ->on('project_renewals')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_facility_renewals');
    }
};
