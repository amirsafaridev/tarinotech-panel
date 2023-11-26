<?php

namespace App\Traits;

trait HasDatatable
{
    abstract public function getDataRoute(): string;

    abstract public function getDataTable(): array;

    abstract public function data();
}
