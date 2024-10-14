<?php

namespace Modules\Project\app\Exports\Admin\Report;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Import the WithColumnTypes interface
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProjectDatatableExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping // Implement WithColumnTypes
{
    public function __construct(public Collection $projectsCollection)
    {
    }

    public function collection(): Collection
    {
        return $this->projectsCollection;
    }

    public function map($row): array
    {
        $adminFullname = isset($row->admin_first_name) && isset($row->admin_last_name)
            ? $row->admin_first_name.' '.$row->admin_last_name
            : 'ناشناس';

        $userFullname = isset($row->user_first_name) && isset($row->user_last_name)
            ? $row->user_first_name.' '.$row->user_last_name
            : 'ناشناس';

        $createdAtFormatted = verta($row->created_at)->format('Y/m/d H:i');

        return [
            $row->id,
            $row->title,
            $adminFullname,
            $userFullname,
            $row->project_types_title,
            $row->project_statuses_title,
            $row->price,
            $row->domain,
            $createdAtFormatted,

        ];
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'عنوان',
            'کارشناس',
            'کارفرما',
            'نوع',
            'وضعیت',
            'قیمت',
            'دامنه',
            'ایجاد',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
