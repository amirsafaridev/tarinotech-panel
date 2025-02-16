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

        $columns = collect($this->columns)->reject(function ($item) {
            return ! $item['visible'];
        });

        return [
            'columns' => $columns,
            'externalFilters' => $this->externalFilters,
        ];
    }
}
