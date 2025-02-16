<?php

namespace Modules\Chat\app\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class ChatBotAction extends Enum implements LocalizedEnum
{
    const MESSAGE = 'MESSAGE';

    const RATE = 'RATE';
}
