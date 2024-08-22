<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use function view;

class SelectEnum extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $enumClass,
        public string $title = '',
        public string $identify = '',
        public string|array|null $old = null,
        public ?string $description = null,
        public bool $multiple = false,
        public bool $withOption = true,
        public bool $isSmall = false,
        public bool $disabled = false,

    ) {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.select-enum');
    }
}
