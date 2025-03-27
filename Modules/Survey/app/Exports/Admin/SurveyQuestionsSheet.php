<?php

namespace Modules\Survey\app\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Modules\Survey\app\Models\Survey;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SurveyQuestionsSheet implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected Survey $survey;

    protected array $stats;

    protected array $questionData;

    public function __construct(Survey $survey, array $stats, array $questionData)
    {
        $this->survey = $survey;
        $this->stats = $stats;
        $this->questionData = $questionData;
    }

    public function title(): string
    {
        return 'سوالات';
    }

    public function collection(): Collection
    {
        return collect($this->questionData);
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'متن سوال',
            'نوع سوال',
            'اجباری',
            'تعداد پاسخ',
            'نرخ پاسخدهی',
        ];
    }

    /**
     * @param  mixed  $row
     */
    public function map($row): array
    {
        return [
            $row['id'],
            $row['text'],
            $row['type'],
            $row['required'],
            $row['answers_count'],
            $row['response_rate'],
        ];
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
                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'لیست سوالات پرسشنامه: '.$this->survey->title);
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
