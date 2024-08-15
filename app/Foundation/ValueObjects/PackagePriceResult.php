<?php

namespace App\Foundation\ValueObjects;

use Modules\Package\app\Models\PackagePrice;

class PackagePriceResult
{
    private ?PackagePrice $price = null;

    private float $percentPrice = 0;

    private int $minimumPrice = 0;

    public function getPrice(): ?PackagePrice
    {
        return $this->price;
    }

    public function setPrice(?PackagePrice $price): PackagePriceResult
    {
        $this->price = $price;

        return $this;
    }

    public function getPercentPrice(): float|int
    {
        return $this->percentPrice;
    }

    public function setPercentPrice(float|int $percentPrice): PackagePriceResult
    {
        $this->percentPrice = $percentPrice;

        return $this;
    }

    public function getMinimumPrice(): int
    {
        return $this->minimumPrice;
    }

    public function setMinimumPrice(int $minimumPrice): PackagePriceResult
    {
        $this->minimumPrice = $minimumPrice;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'price' => $this->price,
            'percentPrice' => $this->percentPrice,
            'minimumPrice' => $this->minimumPrice,
        ];
    }
}
