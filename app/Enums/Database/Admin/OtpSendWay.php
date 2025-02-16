<?php

namespace App\Enums\Database\Admin;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class OtpSendWay extends Enum implements LocalizedEnum
{
    const SMS = 1;

    const EMAIL = 2;

    const GOOGLE_AUTH = 3;
}
