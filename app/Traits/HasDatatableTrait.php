<?php

namespace App\Traits;

trait HasDatatableTrait
{
    abstract public function getDataRoute(): string;

    abstract public function getDataTable(): array;

    abstract public function data();
}
