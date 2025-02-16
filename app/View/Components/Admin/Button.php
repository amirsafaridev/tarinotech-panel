<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use function view;

class Button extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type = 'submit',
        public string $color = 'primary',
        public string $title = '',
        public string $onClick = '',
        public string $identify = '',
        public bool $hasLoading = true,
        public array $addClass = [],
        public bool $disabled = false,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.button');
    }
}
