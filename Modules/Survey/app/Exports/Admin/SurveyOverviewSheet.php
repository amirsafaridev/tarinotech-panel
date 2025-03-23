<?php

namespace Modules\Survey\app\Exports\Admin;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Survey\app\Models\Survey;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyOverviewSheet implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithStyles, WithTitle
{
    protected Survey $survey;

    protected array $stats;

    public function __construct(Survey $survey, array $stats)
    {
        $this->survey = $survey;
        $this->stats = $stats;
    }

    public function title(): string
    {
        return 'خلاصه نظرسنجی';
    }

    public function collection(): Collection
    {
        $data = [
            ['عنوان نظرسنجی', $this->survey->title],
            ['توضیحات', $this->survey->description ?? '-'],
            ['وضعیت', $this->survey->is_active ? 'فعال' : 'غیرفعال'],
            ['تاریخ ایجاد', $this->survey->created_at ? $this->survey->created_at->toJalali()->format('Y/m/d H:i') : '-'],
            ['تاریخ آخرین بروزرسانی', $this->survey->updated_at ? $this->survey->updated_at->toJalali()->format('Y/m/d H:i') : '-'],
            ['تعداد کل پاسخ‌ها', $this->stats['total_responses']],
            ['تعداد کل سوالات', $this->stats['total_questions']],
            ['نرخ تکمیل', $this->stats['completion_rate'].'%'],
            ['میانگین پاسخ به هر سوال', $this->stats['average_answers_per_response']],
            ['زمان متوسط پاسخدهی', $this->stats['average_response_time']],
            ['تاریخ اولین پاسخ', $this->stats['first_response_date'] ? Carbon::parse($this->stats['first_response_date'])->toJalali()->format('Y/m/d H:i') : '-'],
            ['تاریخ آخرین پاسخ', $this->stats['last_response_date'] ? Carbon::parse($this->stats['last_response_date'])->toJalali()->format('Y/m/d H:i') : '-'],
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return ['شاخص', 'مقدار'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
            ],
            'A' => [
                'font' => ['bold' => true],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set RTL direction for the sheet
                $sheet->setRightToLeft(true);

                // Add title for the sheet
                $sheet->mergeCells('A1:B1');
                $sheet->setCellValue('A1', 'گزارش نظرسنجی: '.$this->survey->title);
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Add borders to all cells
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();
                $sheet->getStyle('A1:'.$lastColumn.$lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}
