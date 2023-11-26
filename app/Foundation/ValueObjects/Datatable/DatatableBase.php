<?php

namespace App\Foundation\ValueObjects\Datatable;

class DatatableBase
{
    public array $columns = [];

    public array $externalFilters = [];

    public function addColumn(ColumnOption $option): static
    {
        $this->columns[] = $option->make();

        return $this;
    }

    public function addExternalFilter(ExternalFilter $externalFilter): static
    {
        $this->externalFilters[] = $externalFilter->make();

        return $this;
    }

    public function render(): array
    {
        return [
            'columns' => $this->columns,
            'externalFilters' => $this->externalFilters,
        ];
    }
}
