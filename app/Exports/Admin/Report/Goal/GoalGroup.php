<?php

namespace App\Exports\Admin\Report\Goal;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class GoalGroup implements FromCollection
{
    public function __construct(public Collection $goals)
    {

    }

    public function collection(): Collection
    {
        return $this->goals;
    }
}
