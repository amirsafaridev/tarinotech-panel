<?php

namespace App\Enums\Database\Chat;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ChatType extends Enum implements LocalizedEnum
{
    const Private = 1;

    const Public = 2;

    const Group = 3;

    const Ticket = 4;
}
