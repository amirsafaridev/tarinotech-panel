<?php

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
