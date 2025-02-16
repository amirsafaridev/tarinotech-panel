<?php

namespace App\Enums\Database\Chat;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class AttachmentType extends Enum implements LocalizedEnum
{
    const Video = 1;

    const Photo = 2;

    const Voice = 3;

    const File = 4;
}
