<?php

namespace Modules\Survey\app\Exports\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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

class QuestionDetailSheet implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithStyles, WithTitle
{
    protected Survey $survey;

    protected array $question;

    protected int $questionNumber;

    public function __construct(Survey $survey, array $question, int $questionNumber)
    {
        $this->survey = $survey;
        $this->question = $question;
        $this->questionNumber = $questionNumber;
    }

    public function title(): string
    {
        return 'سوال '.$this->questionNumber;
    }

    public function collection(): Collection
    {
        $data = [
            ['شناسه سوال', $this->question['id']],
            ['متن سوال', $this->question['text']],
            ['نوع سوال', $this->question['type']],
            ['وضعیت', $this->question['required']],
            ['تعداد پاسخ', $this->question['answers_count']],
            ['نرخ پاسخدهی', $this->question['response_rate']],
            ['', ''],
        ];

        // Add option breakdown for choice questions
        if (isset($this->question['options'])) {
            $data[] = ['توزیع پاسخ‌ها', ''];
            $data[] = ['گزینه', 'تعداد', 'درصد'];

            foreach ($this->question['options'] as $optionText => $stats) {
                $data[] = [$optionText, $stats['count'], $stats['percentage']];
            }
        }

        // Add text samples for text questions
        if (isset($this->question['text_samples'])) {
            $data[] = ['', ''];
            $data[] = ['نمونه پاسخ‌های متنی', ''];

            foreach ($this->question['text_samples'] as $index => $sample) {
                $data[] = [($index + 1).'.', $sample];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            7 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFEEEEEE'],
                ],
            ],
            8 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
            ],
            'A1:A6' => [
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
                $sheet->mergeCells('A1:C1');
                $sheet->setCellValue('A1', 'جزئیات سوال: '.Str::limit($this->question['text'], 50));
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Add borders to relevant cells
                $sheet->getHighestRow();
                $sheet->getStyle('A2:B6')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // If we have options or text samples, add borders to those sections too
                if (isset($this->question['options'])) {
                    $startRow = 9;
                    $optionsCount = count($this->question['options']);
                    $sheet->getStyle('A'.$startRow.':C'.($startRow + $optionsCount))->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
            },
        ];
    }
}
