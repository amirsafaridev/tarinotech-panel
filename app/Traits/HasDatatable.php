<?php

namespace App\Traits;

trait HasDatatable
{
    private string $routeData = '';

    private array $columns = [];

    abstract public function getDataRoute(): string;

    abstract public function getColumns(): array;

    abstract public function data();
}
