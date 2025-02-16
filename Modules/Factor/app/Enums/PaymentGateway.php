<?php

namespace Modules\Factor\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PaymentGateway extends Enum implements LocalizedEnum
{
    const SEPEHR = 1;

    const PAYPING = 2;
}
