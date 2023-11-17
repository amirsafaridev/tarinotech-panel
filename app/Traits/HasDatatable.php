<?php

namespace App\Traits;

trait HasDatatable
{
    abstract public function getDataRoute(): string;

    abstract public function getColumns(): array;

    abstract public function data();
}
