<?php

namespace App\Channel;

class SmsChannel
{
    public function send(string $code)
    {
        logger('send sms : '.$code);
    }
}
