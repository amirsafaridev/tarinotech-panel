<?php

namespace App\Enums\Database\SampleMessage;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class MessageType extends Enum implements LocalizedEnum
{
    const AutoSend = 1;

    const ReadyMessage = 2;
}
