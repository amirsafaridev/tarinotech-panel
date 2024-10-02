<?php

namespace Modules\Factor\app\Exports\Admin\Report;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; // Import the WithColumnTypes interface
use Modules\Factor\app\Enums\FactorStatus;
use Modules\Factor\app\Enums\PaymentGateway;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DatatableExport implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping // Implement WithColumnTypes
{
    public function __construct(public Collection $factorsCollection)
    {
    }

    public function collection(): Collection
    {
        return $this->factorsCollection;
    }

    public function map($row): array
    {
        $adminFullname = isset($row->admin_first_name) && isset($row->admin_last_name)
            ? $row->admin_first_name.' '.$row->admin_last_name
            : 'ناشناس';

        $projectTitle = $row->project_title ?? 'پروژه ندارد';

        $finalPriceFormatted = $row->final_price;
        $gatewayDescription = $row->gateway ? PaymentGateway::getDescription($row->gateway) : 'نامشخص';
        $statusDescription = FactorStatus::getDescription($row->status);
        $paidAtFormatted = $row->paid_at ? verta($row->paid_at)->format('Y/m/d H:i') : 'پرداخت نشده';
        $createdAtFormatted = verta($row->created_at)->format('Y/m/d H:i');

        return [
            $row->id,
            $row->title,
            $adminFullname,
            $projectTitle,
            $finalPriceFormatted, // This will be formatted as a number in the Excel file
            $gatewayDescription,
            $statusDescription,
            $paidAtFormatted,
            $createdAtFormatted,
        ];
    }

    public function headings(): array
    {
        return [
            'شناسه',
            'عنوان',
            'کارشناس',
            'پروژه',
            'مبلغ (ریال)',
            'درگاه پرداخت',
            'وضعیت',
            'تاریخ پرداخت',
            'ایجاد',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
