<?php

namespace App\Enums\Database\Project;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

final class WebFacility extends Enum implements LocalizedEnum
{
    const ReceiveSMS = 'Receive SMS';

    const ReceivePaymentGateway = 'Receive Payment Gateway';

    const ReceiveEnamad = 'Receive Enamad';

    const LoginMobile = 'Login Mobile';

    const OnlineChat = 'Online Chat';

    const TicketSystem = 'Ticket System';
}
