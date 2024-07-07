<?php

namespace Modules\Factor\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class FactorStatus extends Enum implements LocalizedEnum
{
    const Paid = 1;

    const Pending = 2;

    const Expired = 3;

    const OnHold = 4;

    const Lock = 5;

    const Draft = 6;

    const PaidManual = 7;

    const PaidWithCheque = 8;

    const CustomerOffer = 9;

    public static function asFilteredSelectArray(): array
    {
        $selectItems = collect(parent::asSelectArray())->reject(function ($key, $value) {

            return $value == self::Paid;
        });

        return $selectItems->toArray();
    }
}
