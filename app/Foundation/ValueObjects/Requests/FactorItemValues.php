<?php

namespace App\Foundation\ValueObjects\Requests;

class FactorItemValues
{
    private int $factor_id;

    private string $title;

    private int $transactionCategoryId;

    private int $price;

    private float $taxRate = 0.09;

    private int $taxAmount;

    private int $discount;

    private int $finalPrice;

    public function getFactorId(): int
    {
        return $this->factor_id;
    }

    public function setFactorId(int $factor_id): FactorItemValues
    {
        $this->factor_id = $factor_id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): FactorItemValues
    {
        $this->title = $title;

        return $this;
    }

    public function getTransactionCategoryId(): int
    {
        return $this->transactionCategoryId;
    }

    public function setTransactionCategoryId(int $transactionCategoryId): FactorItemValues
    {
        $this->transactionCategoryId = $transactionCategoryId;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): FactorItemValues
    {
        $this->price = $price;

        return $this;
    }

    public function getTaxRate(): float
    {
        return $this->taxRate;
    }

    public function getTaxAmount(): int
    {
        return $this->taxAmount;
    }

    public function setTaxAmount(int $taxAmount): FactorItemValues
    {
        $this->taxAmount = $taxAmount;

        return $this;
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function setDiscount(int $discount): FactorItemValues
    {
        $this->discount = $discount;

        return $this;
    }

    public function getFinalPrice(): int
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(int $finalPrice): FactorItemValues
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }
}
