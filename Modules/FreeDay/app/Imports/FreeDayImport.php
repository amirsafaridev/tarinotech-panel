<?php

namespace Modules\FreeDay\app\Imports;

use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\ToModel;
use Modules\FreeDay\app\Models\FreeDay;

class FreeDayImport implements ToModel
{
    public function model(array $row): FreeDay
    {
        return new FreeDay([
            'title' => $row[0],
            'free_at' => Helper::toGregorian($row[1]),
        ]);
    }
}
