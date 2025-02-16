<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Sheet;

class ExcelServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Sheet::listen(AfterSheet::class, function (AfterSheet $event) {
            $event->sheet->getDelegate()->setRightToLeft(true);
        });
    }
}
