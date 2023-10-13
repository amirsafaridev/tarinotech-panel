<?php

namespace App\Enums\Database\Project;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WebFacility extends Enum implements LocalizedEnum
{
    const ReceiveSMS = 'ReceiveSMS';

    const ReceivePaymentGateway = 'ReceivePaymentGateway';

    const ReceiveEnamad = 'ReceiveEnamad';

    const LoginMobile = 'LoginMobile';

    const OnlineChat = 'OnlineChat';

    const TicketSystem = 'TicketSystem';
}
