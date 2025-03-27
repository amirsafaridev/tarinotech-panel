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

class SurveyResponsesSheet implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected Survey $survey;

    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
        // Eager load relationships to improve performance
        $this->survey->load('responses.answers');
    }

    public function title(): string
    {
        return 'پاسخ‌ها';
    }

    public function collection(): Collection
    {
        return $this->survey->responses;
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'نام پاسخ‌دهنده',
            'آدرس IP',
            'تعداد پاسخ به سوالات',
            'وضعیت تکمیل',
            'تاریخ ثبت',
        ];
    }

    /**
     * @param  mixed  $row
     */
    public function map($row): array
    {
        // Calculate completion status
        $requiredQuestions = $this->survey->questions()->where('is_required', true)->count();
        $answeredRequired = $row->answers()->whereIn('survey_question_id', function ($query) {
            $query->select('id')
                ->from('survey_questions')
                ->where('survey_id', $this->survey->id)
                ->where('is_required', true);
        })->count();

        $isComplete = $requiredQuestions > 0 ?
            ($answeredRequired >= $requiredQuestions ? 'تکمیل شده' : 'ناقص') :
            'تکمیل شده';

        return [
            $row->id,
            $row->respondent_name ?? 'ناشناس',
            $row->ip_address ?? '-',
            $row->answers->count(),
            $isComplete,
            $row->created_at ? $row->created_at->toJalali()->format('Y/m/d H:i') : '-',
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
                $sheet->setCellValue('A1', 'پاسخ‌های پرسشنامه: '.$this->survey->title);
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
