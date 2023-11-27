<?php

namespace App\Foundation\ValueObjects\Datatable;

class ExternalFilter
{
    public string $key;

    private string $type = 'string';

    public static function new(): ExternalFilter
    {
        return new self();
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): ExternalFilter
    {
        $this->key = $key;

        return $this;
    }

    public function isNumeric(): ExternalFilter
    {
        $this->type = 'numeric';

        return $this;
    }

    public function isPrice(): ExternalFilter
    {
        $this->type = 'price';

        return $this;
    }

    public function make(): array
    {
        return [
            'key' => $this->key,
            'type' => $this->type,
        ];
    }
}
