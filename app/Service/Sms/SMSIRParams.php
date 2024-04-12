<?php

namespace App\Service\Sms;

class SMSIRParams
{
    private array $params = [];

    public function setParam($value): SMSIRParams
    {
        $this->params[] = $value;

        return $this;
    }

    public function make(): array
    {
        return array_map(function ($key, $value) {
            return [
                'name' => 'PARAMETER'.($key + 1),
                'value' => $value,
            ];
        }, array_keys($this->params), $this->params);
    }
}
