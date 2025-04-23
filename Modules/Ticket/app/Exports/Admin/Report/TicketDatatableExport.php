<?php

namespace Modules\Ticket\app\Exports\Admin\Report;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TicketDatatableExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    public function __construct(public Collection $ticketsCollection)
    {
    }

    public function collection(): Collection
    {
        return $this->ticketsCollection;
    }

    public function map($row): array
    {
        $adminFullname = isset($row->admin_first_name) && isset($row->admin_last_name)
            ? $row->admin_first_name.' '.$row->admin_last_name
            : 'تعیین نشده';

        $userFullname = isset($row->user_first_name) && isset($row->user_last_name)
            ? $row->user_first_name.' '.$row->user_last_name
            : 'ناشناس';

        $lastResponseFormatted = optional($row->last_response_at)->toJalali()->format('Y/m/d H:i') ?? 'بدون پاسخ';
        $createdAtFormatted = verta($row->created_at)->format('Y/m/d H:i');

        return [
            $row->id,
            $row->title,
            $adminFullname,
            $userFullname,
            $row->subject_title,
            $row->status_name,
            $row->priority_name,
            $row->rating ? $row->rating.'/5' : 'ثبت نشده',
            $lastResponseFormatted,
            $createdAtFormatted,
        ];
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'عنوان',
            'پشتیبان',
            'کاربر',
            'موضوع',
            'وضعیت',
            'اولویت',
            'امتیاز',
            'آخرین پاسخ',
            'تاریخ ایجاد',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
