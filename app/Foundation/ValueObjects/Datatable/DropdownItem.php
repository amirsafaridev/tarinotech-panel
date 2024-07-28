<?php

namespace App\Foundation\ValueObjects\Datatable;

class DropdownItem
{
    private string $title;

    private string $link = '#';

    private bool $isHidden = false;

    private bool $targetBlank = false;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function isHidden(): bool
    {
        return $this->isHidden;
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    public function isTargetBlank(): bool
    {
        return $this->targetBlank;
    }

    public function setTargetBlank(bool $targetBlank): self
    {
        $this->targetBlank = $targetBlank;

        return $this;
    }
}
