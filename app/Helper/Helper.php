<?php

namespace App\Helper;

use App\Enums\General\BtnType;
use Carbon\Carbon;
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
}
