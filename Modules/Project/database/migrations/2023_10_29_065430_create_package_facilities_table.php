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
        Schema::create('package_facilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('package_id');

            $table->unsignedInteger('facility_id');
               $table->foreign('package_id')
                ->references('id')
                ->on('packages');

            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
                      
            $table->date('renewal_at')->nullable();
           
            $table->timestamps();

         

           

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_facilities');
    }
};
