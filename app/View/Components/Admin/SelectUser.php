<?php

namespace App\View\Components\Admin;

use App\Enums\Database\User\UserType;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use function view;

class SelectUser extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $identify = 'user_id',
        public string $title = 'انتخاب کاربر',
        public string $old = '',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $users = User::query()
            ->select(['id', 'mobile', 'first_name', 'last_name'])
            ->where('user_type', UserType::Primary)
            ->get();

        return view('components.admin.select-user', compact('users'));
    }
}
