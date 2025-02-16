<?php

use Modules\Factor\app\Models\Factor;
use Modules\Project\app\Enums\ProjectBase;
use Modules\Project\app\Models\ProjectAds;
use Modules\Project\app\Models\ProjectSeo;
use Modules\Project\app\Models\ProjectWeb;

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

if (! function_exists('cleanDomainUrl')) {
    function cleanDomainUrl($url): string
    {
        $url = preg_replace('/^https?:\/\//', '', $url);

        return rtrim($url, '/');
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

if (! function_exists('facilityCalculator')) {
    function facilityCalculator(int $price, int $work, int $financial): string
    {
        return '';
    }
}
if (! function_exists('formatFileSize')) {
    function formatFileSize($sizeInBytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        $i = 0;
        while ($sizeInBytes >= 1024 && $i < count($units) - 1) {
            $sizeInBytes /= 1024;
            $i++;
        }

        return round($sizeInBytes, 2).' '.$units[$i];
    }
}

if (! function_exists('compressHtml')) {
    function compressHtml($html): array|string|null
    {
        $search = [
            '/>[^\S ]+/s',  // remove whitespaces after tags
            '/[^\S ]+</s',  // remove whitespaces before tags
            '/(\s)+/s',       // shorten multiple whitespace sequences
        ];

        $replace = [
            '>',
            '<',
            '\\1',
        ];

        return preg_replace($search, $replace, $html);
    }
}
if (! function_exists('makeUiStar')) {
    function makeUiStar(int $star): string
    {
        $htmlRender = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $star) {
                $htmlRender .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $htmlRender .= '<i class="fal fa-star"></i>';
            }
        }

        return $htmlRender;
    }
}
if (! function_exists('hasAdminPermission')) {
    function hasAdminPermission(string $permissionName): bool
    {
        return auth('admin')->check() && auth('admin')->user()->hasPermissionTo($permissionName);
    }
}

if (! function_exists('hasAdminRole')) {
    function hasAdminRole(int $roleId): bool
    {
        return auth()->user()->hasRole($roleId);
    }
}

if (! function_exists('makeRouteSignRequest')) {
    function makeRouteSignRequest(string $targetType, $targetId): string
    {
        switch ($targetType) {
            case ProjectWeb::class:
                $route = route('admin.contract.project.web', $targetId);
                break;
            case ProjectAds::class:
                $route = route('admin.contract.project.ads', $targetId);
                break;
            case ProjectSeo::class:
                $route = route('admin.contract.project.seo', $targetId);
                break;
            case Factor::class:
                $route = route('admin.contract.factor', $targetId);
                break;
            default:
                $route = '#';
                break;
        }

        return $route;
    }
}

if (! function_exists('makeRouteContractPreview')) {
    function makeRouteContractPreview(string $targetType, $targetId): string
    {
        switch ($targetType) {
            case ProjectWeb::class:
                $route = route('admin.contract.web-project.preview', $targetId);
                break;
            case ProjectSeo::class:
                $route = route('admin.contract.seo-project.preview', $targetId);
                break;
            case Factor::class:
                $route = route('admin.contract.factor.preview', $targetId);
                break;
            default:
                $route = '#';
                break;
        }

        return $route;
    }
}

if (! function_exists('escapeLike')) {
    function escapeLike(string $value): string
    {
        // Escape special characters used in LIKE queries: %, _
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
