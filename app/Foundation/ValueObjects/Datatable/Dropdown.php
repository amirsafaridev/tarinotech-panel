<?php

namespace App\Foundation\ValueObjects\Datatable;

use App\Enums\General\DropdownItemColor;

class Dropdown
{
    private array $items = [];

    private string $buttonColor = 'primary';

    private string $buttonTitle = 'عملیات';

    public function add(DropdownItem $item): static
    {
        $this->items[] = $item;

        return $this;
    }

    public function setButtonColor(DropdownItemColor $color): self
    {
        $this->buttonColor = DropdownItemColor::getBootstrapClass($color->value);

        return $this;
    }

    public function setButtonTitle(string $title): self
    {
        $this->buttonTitle = $title;

        return $this;
    }

    private function renderButton(): string
    {
        return '<button type="button" class="btn btn-sm btn-'.$this->buttonColor.' dropdown-toggle" data-bs-toggle="dropdown">
                    '.$this->buttonTitle.' <span class="caret"></span>
                </button>';
    }

    private function renderItems(): string
    {
        $itemsHtml = '';

        /** @var DropdownItem $item */
        foreach ($this->items as $item) {
            if (! $item->isHidden()) {
                $target = $item->isTargetBlank() ? ' target="_blank"' : '';
                $itemsHtml .= '<li><a href="'.$item->getLink().'"'.$target.'>'.$item->getTitle().'</a></li>';
            }
        }

        return $itemsHtml;
    }

    public function render(): string
    {
        return '<div>
                    '.$this->renderButton().'
                    <ul class="dropdown-menu" role="menu">
                        '.$this->renderItems().'
                    </ul>
                </div>';
    }
}
