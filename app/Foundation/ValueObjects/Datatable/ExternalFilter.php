<?php

namespace App\Foundation\ValueObjects\Datatable;

class ExternalFilter
{
    public string $key;

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

    public function make(): array
    {
        return [
            'key' => $this->key,
        ];
    }
}
