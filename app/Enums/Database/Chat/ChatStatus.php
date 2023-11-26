<?php

namespace App\Enums\Database\Chat;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ChatStatus extends Enum implements LocalizedEnum
{
    const Open = 1;

    const Close = 2;
}
