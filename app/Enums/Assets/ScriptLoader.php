<?php

namespace App\Enums\Assets;

use BenSampo\Enum\Enum;

/**
 * @method static DataTable()
 * @method static MultiSelect()
 * @method static Alert()
 * @method static Select2()
 * @method static DataTableOffline()
 * @method static CKEditor()
 * @method static ChartJs()
 * @method static Datepicker()
 * @method static InputMask()
 * @method static Toast()
 */
final class ScriptLoader extends Enum
{
    const DataTable = 1;

    const MultiSelect = 2;

    const Alert = 3;

    const Select2 = 4;

    const DataTableOffline = 5;

    const CKEditor = 6;

    const ChartJs = 7;

    const Datepicker = 8;

    const InputMask = 9;

    const Toast = 10;
}
