<?php

namespace Modules\FreeDay\app\Imports;

use App\Helpers\Helper;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Modules\FreeDay\app\Models\FreeDay;

class FreeDayImport implements SkipsEmptyRows, ToModel, WithHeadingRow, WithStartRow, WithValidation
{
    public function model(array $row): FreeDay
    {

        return new FreeDay([
            'title' => $row['title'],
            'free_at' => Helper::toGregorian($row['date']),
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
            'date' => 'required|jdate',
        ];
    }

    public function startRow(): int
    {
        return 2;
    }
}
