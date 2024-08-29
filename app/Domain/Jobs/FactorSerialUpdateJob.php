<?php

namespace App\Domain\Jobs;

use Illuminate\Support\Facades\DB;
use Modules\Factor\app\Models\Factor;

class FactorSerialUpdateJob
{
    public function handle(Factor $factor)
    {
        $serial = $this->generateNextFixedNumber($factor);
        $factor->update([
            'serial' => $serial,
        ]);
    }

    protected function generateNextFixedNumber()
    {
        return DB::transaction(function () {
            $lastNumber = DB::table('factors')
                ->lockForUpdate()
                ->max('serial');
            $nextNumber = $lastNumber ? (int) $lastNumber + 1 : 1;

            return str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        });
    }
}
