<?php

namespace App\Exports\Admin\Report\Goal;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GoalPerson implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public Collection $goals)
    {

    }

    public function collection(): Collection
    {
        return $this->goals;
    }

    public function map($row): array
    {
        return [
            $row->type->first_name,
            $row->type->last_name,
            $row->type->email,
            verta($row->start_at)->format('Y-m-d'),
            verta($row->end_at)->format('Y-m-d'),
            $row->profitability ?? 0,
            $row->profitability_dollar ?? 0,
            $row->total_sales ?? 0,
            $row->project_counts,
        ];
    }

    public function headings(): array
    {
        return [
            'نام',
            'نام خانوادگی',
            'پست الکترونیکی',
            'تاریخ شروع',
            'تاریخ پایان',
            'هدف فروش (ریالی)',
            'هدف فروش (دلاری)',
            'فروش',
            'تعداد پروژه',
        ];
    }
}
