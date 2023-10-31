<?php

use App\Enums\Database\Project\ProjectBase;

if (! function_exists('isValidDateFormat')) {
    /**
     * Check if a date string is in a valid 'Y-m-d' format.
     */
    function isValidDateFormat(string $date, string $format = 'Y-m-d'): bool
    {
        return DateTime::createFromFormat($format, $date) !== false;
    }
}

if (! function_exists('calculatePercentageProgress')) {
    /**
     * Calculate the percentage progress of user sales towards a total sales target.
     */
    function calculatePercentageProgress($userSales, $totalSalesTarget): float|int
    {
        if ($totalSalesTarget <= 0) {
            return 0; // Avoid division by zero
        }

        return round(($userSales / $totalSalesTarget) * 100, 2);
    }
}

if (! function_exists('isFormatDate')) {
    function isFormatDate($date): bool
    {
        $pattern = '/^\d{4}\/\d{2}\/\d{2}$/';

        return preg_match($pattern, persianNumberToEnglish($date));
    }
}

if (! function_exists('isJalaliDate')) {
    function isJalaliDate($date): bool
    {
        $verat = verta(persianNumberToEnglish($date));
        if ($verat->year > 1300 && $verat->year < 2000) {
            return true;
        }

        return false;
    }
}

if (! function_exists('persianNumberToEnglish')) {
    function persianNumberToEnglish($string): array|string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($persian, $english, $string);
    }
}

if (! function_exists('arabicToPersianNumeric')) {
    function arabicToPersianNumeric($input): string
    {
        $arabicNumerals = range(0, 9);
        $persianNumerals = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace($arabicNumerals, $persianNumerals, $input);
    }
}

if (! function_exists('formatJalaliDate')) {
    function formatJalaliDate(): string
    {
        return 'd/F/Y';
    }
}

if (! function_exists('formatJalaliDateTime')) {
    function formatJalaliDateTime(): string
    {
        return 'd/F/Y H:i';
    }
}

if (! function_exists('formatJalaliMonth')) {
    function formatJalaliMonth(): string
    {
        return 'd/F';
    }
}

if (! function_exists('generatePersianSlug')) {
    function generatePersianSlug($input): string
    {
        $input = strtolower($input);
        $input = preg_replace('/[^a-z0-9آ-ی]+/u', '-', $input);

        return trim($input, '-');
    }
}

if (! function_exists('cleanAlphaNumeric')) {
    function cleanAlphaNumeric($inputString): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $inputString);
    }
}

if (! function_exists('sanitizedIdentify')) {
    function sanitizedIdentify($identify): string
    {
        return str_replace('[]', '', $identify);
    }
}

if (! function_exists('getRouteProjectType')) {
    function getRouteProjectType(int $base): string
    {
        return match ($base) {
            ProjectBase::Web => 'web',
            ProjectBase::Seo => 'seo',
            ProjectBase::Ads => 'ads',
            default => 'not found',
        };
    }
}
