<?php

namespace Modules\Survey\app\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected Collection $surveys;

    public function __construct(Collection $surveys)
    {
        $this->surveys = $surveys;
    }

    public function collection(): Collection
    {
        return $this->surveys;
    }

    public function title(): string
    {
        return 'لیست پرسش نامه ها';
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'عنوان',
            'کارشناس',
            'تعداد سوالات',
            'تعداد پاسخ‌ها',
            'نیاز به احراز هویت',
            'وضعیت',
            'تاریخ شروع',
            'تاریخ پایان',
            'تاریخ ایجاد',
        ];
    }

    /**
     * @param  mixed  $row
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->title,
            $row->admin_mobile ?? 'نامشخص',
            $row->questions_count,
            $row->responses_count,
            $row->requires_auth ? 'بله' : 'خیر',
            $row->is_active ? 'فعال' : 'غیرفعال',
            $row->start_date ? $row->start_date->toJalali()->format(formatJalaliDate()) : 'نامشخص',
            $row->end_date ? $row->end_date->toJalali()->format(formatJalaliDate()) : 'نامشخص',
            $row->created_at->toJalali()->format(formatJalaliDateTime()),
        ];
    }

    /**
     * @throws Exception
     */
    public function styles(Worksheet $sheet): Worksheet
    {
        $sheet->setRightToLeft(true);
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'EEEEEE',
                ],
            ],
        ]);

        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return $sheet;
    }
}
