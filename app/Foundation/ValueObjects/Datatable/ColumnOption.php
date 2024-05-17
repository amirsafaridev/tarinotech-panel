<?php

namespace App\Foundation\ValueObjects\Datatable;

class ColumnOption
{
    private string $name;

    private bool $sortable = true;

    private bool $searchable = true;

    private bool $visible = true;

    private string $as = '';

    public static function new(): ColumnOption
    {
        return new self();
    }

    public function setName(string $name): ColumnOption
    {
        $this->name = $name;

        return $this;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function setSortable(bool $sortable): ColumnOption
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function setSearchable(bool $searchable): ColumnOption
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function removeAction(): ColumnOption
    {
        $this->searchable = false;
        $this->sortable = false;

        return $this;
    }

    public function getAs(): string
    {
        return $this->as;
    }

    public function setAs(string $as): ColumnOption
    {
        $this->as = $as;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): ColumnOption
    {
        $this->visible = $visible;

        return $this;
    }

    public function make(): array
    {
        return [
            'name' => $this->name,
            'sortable' => $this->sortable,
            'searchable' => $this->searchable,
            'as' => $this->as,
            'visible' => $this->visible,
        ];
    }
}
