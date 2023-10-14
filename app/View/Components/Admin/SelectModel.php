<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use function view;

class SelectModel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $items,
        public string $key = 'title',
        public string $value = 'id',
        public string $title = '',
        public string $identify = '',
        public array|string|null $old = null,
        public bool $disabled = false,
        public bool $isSmall = false,
        public bool $multiple = false,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.select-model');
    }
}
