<?php

namespace App\Service\Sms;

class SMSIRParams
{
    private array $params = [];

    public function setParam($name, $value): SMSIRParams
    {
        $this->params[] = [
            'name' => $name,
            'value' => $value,
        ];

        return $this;
    }

    public function make(): array
    {
        return $this->params;
    }
}
