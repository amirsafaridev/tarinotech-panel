<?php

namespace Modules\Survey\app\Exports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Modules\Survey\app\Enums\Database\QuestionTypeEnum;
use Modules\Survey\app\Models\Survey;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ResponseExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected Survey $survey;

    protected Collection $responses;

    protected Collection $questions;

    public function __construct(Survey $survey, Collection $responses)
    {
        $this->survey = $survey;
        $this->responses = $responses;
        $this->questions = $survey->questions()->orderBy('order')->get();
    }

    public function title(): string
    {
        return 'پاسخ‌های نظرسنجی';
    }

    public function collection(): Collection
    {
        return $this->responses;
    }

    public function headings(): array
    {
        $headings = [
            'شناسه پاسخ',
            'نام پاسخ دهنده',
            'ایمیل',
            'آی‌پی',
            'تاریخ ثبت',
        ];

        foreach ($this->questions as $question) {
            $headings[] = $question->question_text.($question->is_required ? ' (اجباری)' : '');
        }

        return $headings;
    }

    public function map($row): array
    {
        $row->load(['answers.question', 'answers.options.questionOption']);

        $result = [
            $row->id,
            $row->respondent_name ?? 'نامشخص',
            $row->respondent_email ?? 'ندارد',
            $row->ip_address,
            $row->created_at->toJalali()->format('Y/m/d H:i'),
        ];

        $answersMap = [];
        foreach ($row->answers as $answer) {
            $answersMap[$answer->survey_question_id] = $answer;
        }

        foreach ($this->questions as $question) {
            $questionId = $question->id;
            $answer = $answersMap[$questionId] ?? null;

            if (! $answer) {
                $result[] = 'بدون پاسخ';

                continue;
            }

            switch ($question->question_type) {
                case QuestionTypeEnum::Text:
                    $result[] = $answer->answer_text ?? 'بدون پاسخ';
                    break;

                case QuestionTypeEnum::Single:
                case QuestionTypeEnum::Multiple:
                    $selectedOptions = [];
                    foreach ($answer->options as $option) {
                        $selectedOptions[] = $option->questionOption->option_text ?? '';
                    }
                    $result[] = ! empty($selectedOptions) ? implode(' | ', $selectedOptions) : 'بدون پاسخ';
                    break;

                default:
                    $result[] = 'نوع سوال نامشخص';
                    break;
            }
        }

        return $result;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E0E0E0'],
                ],
            ],
            'A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],
            'C' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],
            'D' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'E' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }
}
