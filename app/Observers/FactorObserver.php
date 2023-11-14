<?php

namespace App\Observers;

use App\Models\Factor;

class FactorObserver
{
    /**
     * Handle events after all transactions are committed.
     */
    public bool $afterCommit = true;

    /**
     * Handle the Factor "created" event.
     */
    public function created(Factor $factor): void
    {
        $this->updateFinalPrice($factor);
    }

    /**
     * Handle the Factor "updated" event.
     */
    public function updated(Factor $factor): void
    {
        $this->updateFinalPrice($factor);
    }

    private function updateFinalPrice(Factor $factor)
    {

        $finalPrice = $factor->items()->sum('final_price');
        if ($factor->final_price !== $finalPrice) {
            $factor->final_price = $finalPrice;
            $factor->save();
        }

    }
}
