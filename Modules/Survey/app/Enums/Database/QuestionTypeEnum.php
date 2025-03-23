<?php

namespace Modules\Survey\app\Enums\Database;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class QuestionTypeEnum extends Enum implements LocalizedEnum
{
    const Single = 1;

    const Multiple = 2;

    const Text = 3;
}
