<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function view;

class Input extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type = 'text',
        public string $title = '',
        public string $identify = '',
        public ?string $description = '',
        public array $addClass = [],
        public ?string $old = null,
        public bool $disabled = false,
        public bool $readOnly = false,
        public bool $isSmall = false,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.input');
    }
}
