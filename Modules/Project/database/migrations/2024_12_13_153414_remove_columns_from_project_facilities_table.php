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
        Schema::table('project_facilities', function (Blueprint $table) {
            $table->dropColumn([
                'price_type',
                'price_value',
                'work_cycle',
                'work_cycle_value',
                'financial_cycle',
                'financial_cycle_value',
                'added_at',
                'description',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_facilities', function (Blueprint $table) {
            $table->unsignedTinyInteger('price_type')->default(PriceType::None);
            $table->unsignedInteger('price_value')->default(0);
            $table->unsignedTinyInteger('work_cycle')->default(WorkCycle::None);
            $table->date('work_cycle_value')->nullable();
            $table->unsignedTinyInteger('financial_cycle')->default(FinancialCycle::None);
            $table->unsignedInteger('financial_cycle_value')->default(0);
            $table->date('renewal_at')->nullable();
            $table->date('added_at');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('status')->default(FacilityStatus::Renewal);
        });
    }
};
