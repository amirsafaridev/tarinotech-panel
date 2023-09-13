<?php

namespace App\Enums\Assets;

use BenSampo\Enum\Enum;

/**
 * @method static static DataTable()
 * @method static static Toast()
 * @method static static MultiSelect()
 * @method static static Alert()
 * @method static static Select2()
 * @method static static Quill()
 * @method static static Datepicker()
 * @method static static Acf()
 */
final class StyleLoader extends Enum
{
    const DataTable = 1;

    const Toast = 2;

    const MultiSelect = 3;

    const Alert = 4;

    const Select2 = 5;

    const Quill = 6;

    const Datepicker = 7;

    const Acf = 8;
}
