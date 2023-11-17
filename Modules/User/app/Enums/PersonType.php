<?php

namespace Modules\User\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class PersonType extends Enum implements LocalizedEnum
{
    const Legal = 1;

    const Person = 2;
}
