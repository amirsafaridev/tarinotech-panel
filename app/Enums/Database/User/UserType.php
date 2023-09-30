<?php

namespace App\Enums\Database\User;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class UserType extends Enum implements LocalizedEnum
{
    const Primary = 1;

    const Presenter = 2;
}
