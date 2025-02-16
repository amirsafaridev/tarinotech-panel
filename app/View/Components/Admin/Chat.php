<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Modules\Support\app\Models\Chat as ChatModel;

use function view;

class Chat extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?ChatModel $chat = null,
        public ?bool $hasEndButton = false
    ) {

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.chat');
    }
}
