<?php

use App\Domain\Jobs\FactorSerialUpdateJob;
use Illuminate\Database\Migrations\Migration;
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Models\Factor;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Factor::query()->where('status', FactorStatus::Paid)
            ->where('is_official', true)
            ->chunkById(100, function ($factors) {
                foreach ($factors as $factor) {
                    resolve(FactorSerialUpdateJob::class)->handle($factor);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Factor::query()->where('status', FactorStatus::Paid)
            ->where('is_official', true)
            ->update(['serial' => null]);
    }
};
