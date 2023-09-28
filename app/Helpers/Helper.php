<?php

namespace App\Helpers;

use App\Enums\General\BtnType;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Verta;

class Helper
{
    public static function btnMaker($type, $route = '', $title = ''): string
    {
        return match ($type) {
            BtnType::Warning => '<a target="_blank" href="'.$route.'" class="btn btn-sm btn-warning mx-1">'.$title.'</a>',
            BtnType::Danger => '<a target="_blank" href="'.$route.'" class="btn btn-sm btn-danger mx-1">'.$title.'</a>',
            BtnType::Info => '<a target="_blank" href="'.$route.'" class="btn btn-sm btn-info mx-1">'.$title.'</a>',
            BtnType::Success => '<a target="_blank" href="'.$route.'" class="btn btn-sm btn-success mx-1">'.$title.'</a>',
            default => '',
        };

    }

    public static function abbreviateNumber($number)
    {
        if ($number >= 1e12) {
            return round($number / 1e12, 1).'T';
        } elseif ($number >= 1e9) {
            return round($number / 1e9, 1).'B';
        } elseif ($number >= 1e6) {
            return round($number / 1e6, 1).'M';
        } elseif ($number >= 1e3) {
            return round($number / 1e3, 1).'k';
        }

        return $number;
    }

    public static function getRouteSmall(): string
    {
        $routeName = Route::currentRouteName();
        if (str($routeName)->contains('admin.admin')) {
            return 'admin/admin';
        }

        if (str($routeName)->contains('admin.meet')) {
            return 'admin/meet';
        }

        $routeParts = explode('.', $routeName);

        return $routeParts[1];
    }

    public static function permissionReadAble($permission): string
    {
        return strtoupper(str_replace('_', ' ', $permission));
    }

    public static function randAlphaNumeric($length = 7): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function randNumeric($length = 7): string
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function persianNumberToEnglish($string): array|string
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        return str_replace($persian, $english, $string);
    }

    public static function uniqueIdentity($table, $field): string
    {
        $code = self::randAlphaNumeric();
        if (DB::table($table)->where($field, $code)->exists()) {
            self::uniqueIdentity($table, $field);
        }

        return $code;
    }

    public static function toGregorian(string $date): string
    {
        $vertaJalaliInstance = Verta::parse($date);
        $gregorian = Verta::jalaliToGregorian(
            $vertaJalaliInstance->year,
            $vertaJalaliInstance->month,
            $vertaJalaliInstance->day
        );

        return Carbon::createFromDate($gregorian[0], $gregorian[1], $gregorian[2])->format('Y-m-d');
    }

    public static function vertaRangeDateByMonth(int $nextMonth = 3): Collection
    {
        $dateItems = [];
        $date = verta()->startMonth();
        $fromAt = $date->copy();
        for ($i = 1; $i <= $nextMonth; $i++) {
            $dateInMonth = $date->daysInMonth;
            $dateItems[] = [
                'start' => $date->copy(),
                'end' => $date->copy()->addDays($dateInMonth - 1),
            ];
            $date->addDays($dateInMonth);
        }
        $toAt = $date;

        return collect([
            'items' => $dateItems,
            'from_at' => $fromAt,
            'to_at' => $toAt,
        ]);
    }

    /**
     * @throws Exception
     */
    public static function vertaInstanceToGregorian($instance, string $format = 'Y-m-d'): string
    {
        try {
            return $instance->toCarbon()->format($format);
        } catch (Exception $e) {
            report($e);
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public static function vertaMonthGenerator(): Collection
    {
        try {
            $verta = verta()->startYear();
            $months = [];
            for ($i = 1; $i <= 12; $i++) {

                $months[] = [
                    'start' => $verta->startMonth()->format('Y-m-d'),
                    'end' => $verta->endMonth()->format('Y-m-d'),
                    'month_name' => $verta->endMonth()->format('F'),
                    'gregorian_start' => $verta->startMonth()->toCarbon()->format('Y-m-d'),
                    'gregorian_end' => $verta->endMonth()->toCarbon()->format('Y-m-d'),
                    'gregorian_month_name' => $verta->endMonth()->toCarbon()->format('F'),
                ];
                $verta = $verta->addMonth();
            }

            return collect($months);
        } catch (Exception $e) {
            report($e);
            throw new Exception($e->getMessage());
        }
    }

    public static function checkDateInGeneratedDatesByVerta(Collection $vertaRangeDateCollection, Collection $olds): Collection
    {
        return collect($vertaRangeDateCollection->get('items', []))->map(function ($item) use ($olds) {
            $data = $item;

            $adminGoal = $olds
                ->where('start_at', Helper::vertaInstanceToGregorian($item['start']))
                ->where('end_at', Helper::vertaInstanceToGregorian($item['end']))
                ->first();

            if ($adminGoal) {
                $data['profitability'] = $adminGoal->profitability;
                $data['profitability_dollar'] = $adminGoal->profitability_dollar;
            } else {
                $data['profitability'] = 0;
                $data['profitability_dollar'] = 0;
            }

            return $data;
        });
    }
}
