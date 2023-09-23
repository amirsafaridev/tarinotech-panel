<?php

namespace App\View\Components;

use App\Helpers\Helper;
use Closure;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectMonth extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title = 'از ماه',
        public string $value = 'gregorian_start',
        public string $identify = 'start_at',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        try {
            $months = Helper::vertaMonthGenerator();

            return view('components.select-month', compact('months'));
        } catch (Exception $e) {
            report($e);

            return $e->getMessage();
        }

    }
}
